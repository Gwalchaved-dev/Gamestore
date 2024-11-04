<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RedirectAdminListener
{
    private Security $security;
    private RouterInterface $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        // Récupérer l'utilisateur actuel
        $user = $this->security->getUser();

        // Si l'utilisateur est connecté et est un administrateur
        if ($user && $this->security->isGranted('ROLE_ADMIN')) {
            $request = $event->getRequest();

            // Vérifie si l'utilisateur tente d'accéder à la page du compte
            if ($request->attributes->get('_route') === 'app_account') {
                // Redirige vers la page d'administration
                $adminUrl = $this->router->generate('admin_dashboard'); // Assurez-vous que cette route existe dans vos routes
                $event->setResponse(new RedirectResponse($adminUrl));
            }
        }
    }
}