<?php

namespace App\EventSubscriber;

use App\Repository\AgenceRepository;
use Twig\Environment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TwigGlobalSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $agenceRepository;

    public function __construct(Environment $twig, AgenceRepository $agenceRepository)
    {
        $this->twig = $twig;
        $this->agenceRepository = $agenceRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Récupérer toutes les agences
        $agences = $this->agenceRepository->findAll();

        // Rendre les agences globalement disponibles dans toutes les vues Twig
        $this->twig->addGlobal('agences', $agences);
    }
}