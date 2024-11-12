<?php

namespace App\Controller;

use App\Entity\CartJeuxVideos;
use App\Entity\JeuxVideos;
use App\Entity\ShoppingCart;
use App\Entity\Command;
use App\Entity\Agence;
use App\Repository\CartJeuxVideosRepository;
use App\Repository\AgenceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function showCart(CartJeuxVideosRepository $cartJeuxVideosRepository, AgenceRepository $agenceRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);
        $cartItems = $shoppingCart->getCartJeuxVideos();
        $agences = $agenceRepository->findAll();
        $totalPrice = $this->calculateTotalPrice($cartItems);

        return $this->render('base.html.twig', [
            'cart_items' => $cartItems,
            'cart_items_count' => count($cartItems),
            'agences' => $agences,
            'total_price' => $totalPrice,
        ]);
    }

    #[Route('/cart/validate', name: 'cart_validate', methods: ['POST', 'GET'])]
    public function validateCart(Request $request, Security $security, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $security->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);
        $cartItems = $shoppingCart->getCartJeuxVideos();

        if ($cartItems->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_show');
        }

        $agence = $entityManager->getRepository(Agence::class)->find($request->request->get('agencySelect'));

        if (!$agence) {
            $this->addFlash('error', 'Agence non trouvée.');
            return $this->redirectToRoute('cart_show');
        }

        // Création de la commande
        $order = new Command();
        $order->setUser($user);
        $order->setDate(new \DateTime());
        $order->setTotal($this->calculateTotalPrice($cartItems));
        $order->setAgence($agence);

        // Ajout des jeux et informations à la commande
        foreach ($cartItems as $item) {
            $order->addCartJeuxVideo($item);
            // Enregistrement du titre, genre et quantité de chaque jeu dans la commande
            $order->setTitre($item->getJeuxVideo()->getTitre());
            $order->setGenre($item->getJeuxVideo()->getGenre()); // Genre
            $order->setQuantite($item->getQuantite()); // Quantité
            $entityManager->persist($item);
        }

        // Persistance de la commande et mise à jour de la base de données
        $entityManager->persist($order);
        $entityManager->flush();

        // Envoi d'email de confirmation à l'utilisateur
        $email = (new Email())
            ->from('no-reply@gamestore.com')
            ->to($user->getEmail())
            ->subject('Confirmation de votre commande')
            ->html($this->renderView('email/confirmation_commande.html.twig', [
                'user' => $user,
                'cartItems' => $cartItems,
                'totalPrice' => $order->getTotal(),
                'agence' => $order->getAgence(),
            ]));

        $mailer->send($email);

        // Vider le panier après la validation de la commande
        $shoppingCart->getCartJeuxVideos()->clear();
        $entityManager->persist($shoppingCart);
        $entityManager->flush();

        // Confirmation de la commande dans le frontend
        $this->addFlash('success', 'Merci pour votre commande, elle a bien été prise en compte.');

        return $this->redirectToRoute('account');
    }

    #[Route('/cart/checkout', name: 'cart_checkout', methods: ['POST'])]
    public function checkout(Security $security, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = $security->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);
        $cartItems = $shoppingCart->getCartJeuxVideos();

        if ($cartItems->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_show');
        }

        $order = new Command();
        $order->setUser($user);
        $order->setDate(new \DateTime());
        $order->setTotal($this->calculateTotalPrice($cartItems));
        $order->setAgence($entityManager->getRepository(Agence::class)->findOneBy(['id' => 1])); // Default agency for checkout

        // Ajout des jeux et informations à la commande
        foreach ($cartItems as $item) {
            $order->addCartJeuxVideo($item);
            $order->setTitre($item->getJeuxVideo()->getTitre());
            $order->setGenre($item->getJeuxVideo()->getGenre()); // Genre
            $order->setQuantite($item->getQuantite()); // Quantité
            $item->setShoppingCart(null); // Retirer l'élément du panier après validation
            $entityManager->persist($item);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        // Envoi de l'email de confirmation
        $email = (new Email())
            ->from('no-reply@gamestore.com')
            ->to($user->getEmail())
            ->subject('Confirmation de votre commande')
            ->html($this->renderView('email/confirmation_commande.html.twig', [
                'user' => $user,
                'cartItems' => $cartItems,
                'totalPrice' => $order->getTotal(),
                'agence' => $order->getAgence(),
            ]));

        $mailer->send($email);

        // Vider le panier après la commande
        $shoppingCart->getCartJeuxVideos()->clear();
        $entityManager->persist($shoppingCart);
        $entityManager->flush();

        $this->addFlash('success', 'Merci pour votre commande, elle a bien été prise en compte.');

        return $this->redirectToRoute('account');
    }

    #[Route('/cart/empty', name: 'cart_empty', methods: ['POST'])]
    public function emptyCart(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user || !($user instanceof User)) {
            return $this->json(['success' => false, 'error' => 'User not logged in'], 403);
        }

        $shoppingCart = $user->getShoppingCart();

        if (!$shoppingCart) {
            return $this->json(['success' => false, 'error' => 'No shopping cart found'], 404);
        }

        foreach ($shoppingCart->getCartJeuxVideos() as $cartItem) {
            $entityManager->remove($cartItem);
        }

        $entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/panier/ajouter/{id}', name: 'ajouter_panier', methods: ['POST'])]
    public function ajouterAuPanier(int $id, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !($user instanceof User)) {
            return $this->redirectToRoute('app_login');
        }

        $shoppingCart = $this->getOrCreateShoppingCart($user, $entityManager);
        $jeu = $entityManager->getRepository(JeuxVideos::class)->find($id);

        if (!$jeu) {
            throw $this->createNotFoundException('Le jeu vidéo n\'existe pas.');
        }

        $cartItem = $entityManager->getRepository(CartJeuxVideos::class)->findOneBy([
            'shoppingCart' => $shoppingCart,
            'jeuxVideo' => $jeu
        ]);

        if (!$cartItem) {
            $cartItem = new CartJeuxVideos();
            $cartItem->setShoppingCart($shoppingCart);
            $cartItem->setJeuxVideo($jeu);
            $cartItem->setQuantite(1);

            $entityManager->persist($cartItem);
        } else {
            $cartItem->setQuantite($cartItem->getQuantite() + 1);
        }

        $entityManager->flush();

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/panier/update-quantite', name: 'update_cart_quantity', methods: ['POST'])]
    public function updateQuantite(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user || !($user instanceof User)) {
            return $this->json(['error' => 'User not logged in'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $itemId = $data['itemId'] ?? null;
        $quantite = $data['quantite'] ?? null;

        if (!$itemId || !$quantite || $quantite < 1) {
            return $this->json(['error' => 'Invalid item ID or quantity'], 400);
        }

        $shoppingCart = $user->getShoppingCart();
        if (!$shoppingCart) {
            return $this->json(['error' => 'Aucun panier trouvé pour l\'utilisateur'], 404);
        }

        $cartItem = $entityManager->getRepository(CartJeuxVideos::class)->findOneBy([
            'id' => $itemId,
            'shoppingCart' => $shoppingCart
        ]);

        if (!$cartItem) {
            return $this->json(['error' => 'Cart item not found or does not belong to this user'], 404);
        }

        $cartItem->setQuantite($quantite);
        $entityManager->persist($cartItem);
        $entityManager->flush();

        $cartItems = $shoppingCart->getCartJeuxVideos();
        $totalPrice = $this->calculateTotalPrice($cartItems);

        return $this->json(['success' => true, 'totalPrice' => $totalPrice]);
    }

    private function calculateTotalPrice($cartItems): float
    {
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item->getJeuxVideo()->getPrix() * $item->getQuantite();
        }
        return $totalPrice;
    }

    private function getOrCreateShoppingCart($user, EntityManagerInterface $entityManager): ShoppingCart
    {
        $shoppingCart = $user->getShoppingCart();

        if ($shoppingCart === null) {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->setUser($user);
            $entityManager->persist($shoppingCart);
            $entityManager->flush();
            $user->setShoppingCart($shoppingCart);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $shoppingCart;
    }
}