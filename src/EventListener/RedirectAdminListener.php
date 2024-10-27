<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RedirectAdminListener
{
    private $security;
    private $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Récupérer l'utilisateur actuel
        $user = $this->security->getUser();

        // Si l'utilisateur est connecté et est un administrateur
        if ($user && $this->security->isGranted('ROLE_ADMIN')) {
            $request = $event->getRequest();

            // Si l'utilisateur tente d'accéder à la page du compte
            if ($request->get('_route') === 'app_account') {
                // Rediriger vers la page d'administration
                $adminUrl = $this->router->generate('admin_dashboard'); // Assurez-vous que la route existe
                $event->setResponse(new RedirectResponse($adminUrl));
            }
        }
    }
}