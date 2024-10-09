<?php

namespace App\EventSubscriber;

use App\Repository\CartJeuxVideosRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;
use App\Entity\User;

class CartSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private Environment $twig;
    private CartJeuxVideosRepository $cartRepository;

    public function __construct(Security $security, Environment $twig, CartJeuxVideosRepository $cartRepository)
    {
        $this->security = $security;
        $this->twig = $twig;
        $this->cartRepository = $cartRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $user = $this->security->getUser();

        // Vérification si l'utilisateur est bien une instance de User
        if ($user instanceof User) {
            // Récupérer le panier de l'utilisateur
            $shoppingCart = $user->getShoppingCart();

            if ($shoppingCart) {
                $cartItems = $shoppingCart->getCartJeuxVideos();
                $cartItemsCount = count($cartItems);

                // Calcul du prix total
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $totalPrice += $item->getJeuxVideo()->getPrix() * $item->getQuantite();
                }

                // Ajouter les variables Twig globales
                $this->twig->addGlobal('cart_items', $cartItems);
                $this->twig->addGlobal('cart_items_count', $cartItemsCount);
                $this->twig->addGlobal('total_price', $totalPrice);
            } else {
                // Si pas de panier, définir des valeurs par défaut
                $this->twig->addGlobal('cart_items', []);
                $this->twig->addGlobal('cart_items_count', 0);
                $this->twig->addGlobal('total_price', 0);
            }
        } else {
            // Si l'utilisateur n'est pas connecté, définir des valeurs par défaut
            $this->twig->addGlobal('cart_items', []);
            $this->twig->addGlobal('cart_items_count', 0);
            $this->twig->addGlobal('total_price', 0);
        }
    }
}