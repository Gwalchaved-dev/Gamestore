<?php

namespace App\Controller;

use App\Entity\CartJeuxVideos;
use App\Entity\JeuxVideos;
use App\Entity\ShoppingCart;
use App\Repository\CartJeuxVideosRepository;
use App\Repository\AgenceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function showCart(CartJeuxVideosRepository $cartJeuxVideosRepository, AgenceRepository $agenceRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        // Vérification si l'utilisateur est connecté et de type User
        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer ou créer le panier de l'utilisateur
        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);

        // Récupérer les articles du panier
        $cartItems = $shoppingCart->getCartJeuxVideos();
        $agences = $agenceRepository->findAll();  // Récupérer toutes les agences

        // Calculer le prix total du panier
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->getJeuxVideo()->getPrix() * $item->getQuantite();
        }

        // Afficher le template avec la modal du panier
        return $this->render('base.html.twig', [
            'cart_items' => $cartItems,
            'cart_items_count' => count($cartItems),  // Nombre d'articles dans le panier
            'agences' => $agences,  // Passer les agences à la vue
            'total_price' => $totalPrice,  // Passer le prix total à la vue
        ]);
    }

    #[Route('/cart/validate', name: 'cart_validate', methods: ['POST'])]
    public function validateCart(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        // Vérification si l'utilisateur est connecté et de type User
        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer ou créer le panier de l'utilisateur
        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);

        // Récupérer les articles du panier
        $cartItems = $shoppingCart->getCartJeuxVideos();

        if ($cartItems->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_show');
        }

        // Sauvegarder la commande et marquer les articles
        foreach ($cartItems as $item) {
            $entityManager->persist($item);
        }

        // Valider les changements en base de données
        $entityManager->flush();

        $this->addFlash('success', 'Votre commande a été validée avec succès.');

        return $this->redirectToRoute('galerie');
    }

    #[Route('/panier/ajouter/{id}', name: 'ajouter_panier', methods: ['POST'])]
    public function ajouterAuPanier(int $id, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer ou créer le panier de l'utilisateur
        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);

        // Récupérer l'article (jeu vidéo) par son ID
        $jeu = $entityManager->getRepository(JeuxVideos::class)->find($id);

        if (!$jeu) {
            throw $this->createNotFoundException('Le jeu vidéo n\'existe pas.');
        }

        // Vérifier si l'article est déjà dans le panier
        $cartItem = $entityManager->getRepository(CartJeuxVideos::class)->findOneBy([
            'shoppingCart' => $shoppingCart,
            'jeuxVideo' => $jeu
        ]);

        if (!$cartItem) {
            // Si l'article n'est pas encore dans le panier, on l'ajoute
            $cartItem = new CartJeuxVideos();
            $cartItem->setShoppingCart($shoppingCart);
            $cartItem->setJeuxVideo($jeu);
            $cartItem->setQuantite(1); // Par exemple, quantité par défaut

            $entityManager->persist($cartItem);
        } else {
            // Si l'article est déjà dans le panier, on augmente la quantité
            $cartItem->setQuantite($cartItem->getQuantite() + 1);
        }

        $entityManager->flush();

        // Rediriger vers la page du panier ou un message de succès
        return $this->redirectToRoute('cart_show');
    }

    // Méthode pour récupérer ou créer un panier pour l'utilisateur
    private function getOrCreateShoppingCart($user, EntityManagerInterface $entityManager): ShoppingCart
    {
        $shoppingCart = $user->getShoppingCart();

        // Si l'utilisateur n'a pas de panier, on en crée un
        if ($shoppingCart === null) {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->setUser($user);  // Associe le panier à l'utilisateur
            $entityManager->persist($shoppingCart);
            $entityManager->flush(); // Flush ici pour avoir un ID pour le panier
            $user->setShoppingCart($shoppingCart);  // Met à jour l'utilisateur avec le panier
            $entityManager->persist($user);
            $entityManager->flush();  // Persist changes on both user and shopping cart
        }

        return $shoppingCart;
    }

    // Nouvelle méthode pour mettre à jour la quantité des articles dans le panier
    #[Route('/panier/update-quantite', name: 'update_cart_quantity', methods: ['POST'])]
    public function updateQuantite(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        if (!$user || !($user instanceof User)) {
            return $this->json(['error' => 'User not logged in'], 403);
        }

        // Récupérer les données envoyées via la requête POST
        $data = json_decode($request->getContent(), true);
        $itemId = $data['itemId'] ?? null;
        $quantite = $data['quantite'] ?? null;

        if (!$itemId || !$quantite || $quantite < 1) {
            return $this->json(['error' => 'Invalid item ID or quantite'], 400);
        }

        // Récupérer le panier de l'utilisateur
        $shoppingCart = $user->getShoppingCart();
        if (!$shoppingCart) {
            return $this->json(['error' => 'No shopping cart found for user'], 404);
        }

        // Récupérer l'article du panier correspondant
        $cartItem = $entityManager->getRepository(CartJeuxVideos::class)->find($itemId);

        if (!$cartItem || $cartItem->getShoppingCart() !== $shoppingCart) {
            return $this->json(['error' => 'Cart item not found or does not belong to this user'], 404);
        }

        // Mettre à jour la quantite
        $cartItem->setQuantite($quantite);
        $entityManager->persist($cartItem);
        $entityManager->flush();

        // Recalculer le prix total du panier
        $cartItems = $shoppingCart->getCartJeuxVideos();
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->getJeuxVideo()->getPrix() * $item->getQuantite();
        }

        return $this->json(['success' => true, 'totalPrice' => $totalPrice]);
    }

    // Nouvelle méthode pour créer des paniers pour tous les utilisateurs
    #[Route('/admin/create-cart-users', name: 'create_cart_users')]
    public function createCartForUsers(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getShoppingCart() === null) {
                $shoppingCart = new ShoppingCart();
                $shoppingCart->setUser($user);
                $entityManager->persist($shoppingCart);
                $user->setShoppingCart($shoppingCart);
                $entityManager->persist($user);
            }
        }

        $entityManager->flush();

        return new Response('Panier créé pour les utilisateurs sans panier.');
    }
}