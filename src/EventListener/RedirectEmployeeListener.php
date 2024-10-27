<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Psr\Log\LoggerInterface;

class RedirectEmployeeListener
{
    private Security $security;
    private RouterInterface $router;
    private LoggerInterface $logger;

    public function __construct(Security $security, RouterInterface $router, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // Récupérer l'utilisateur actuel
        $user = $this->security->getUser();
        $request = $event->getRequest();

        // Si l'utilisateur est connecté, est un employé et tente d'accéder à /account
        if ($user && $this->security->isGranted('ROLE_EMPLOYEE') && $request->get('_route') === 'account') {
            $this->logger->info('Redirection de l\'employé vers /employee/space');

            // Redirige vers l'espace employé
            $employeeUrl = $this->router->generate('app_employee');
            $event->setResponse(new RedirectResponse($employeeUrl));
        }
    }
}