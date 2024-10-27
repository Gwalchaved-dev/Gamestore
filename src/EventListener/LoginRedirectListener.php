<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class LoginRedirectListener
{
    private UrlGeneratorInterface $urlGenerator;
    private LoggerInterface $logger;

    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $roles = $user->getRoles();

        $this->logger->info('User logged in successfully', [
            'user' => $user->getUserIdentifier(),
            'roles' => $roles,
        ]);

        // Détermine l'URL de redirection en fonction du rôle
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $redirectUrl = $this->urlGenerator->generate('app_admin');
        } elseif (in_array('ROLE_EMPLOYEE', $roles, true)) {
            $redirectUrl = $this->urlGenerator->generate('app_employee');
        } elseif (in_array('ROLE_USER', $roles, true)) {
            $redirectUrl = $this->urlGenerator->generate('app_homepage');
        } else {
            // Redirection par défaut si aucun rôle spécifique
            $redirectUrl = $this->urlGenerator->generate('app_homepage');
        }

        // Définit la réponse de redirection directement
        $response = new RedirectResponse($redirectUrl);
        $event->getRequest()->getSession()->set('_security.main.target_path', $redirectUrl);  // Optionnel si tu veux garder le chemin cible
        $event->getRequest()->getSession()->save();  // Assure-toi que la session est sauvegardée

        // Redirige immédiatement
        $event->getRequest()->attributes->set('_security.main.interactive_login', $response);
    }
}