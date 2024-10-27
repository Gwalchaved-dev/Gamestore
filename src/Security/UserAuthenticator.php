<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;
use App\Repository\EmployeeRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private Security $security;
    private LoggerInterface $logger;
    private UserRepository $userRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Security $security,
        LoggerInterface $logger,
        UserRepository $userRepository,
        EmployeeRepository $employeeRepository
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function supports(Request $request): bool
    {
        $isLoginRoute = $request->attributes->get('_route') === self::LOGIN_ROUTE;
        $isPostMethod = $request->isMethod('POST');

        $this->logger->info('Checking if request supports authentication:', [
            'route' => $request->attributes->get('_route'),
            'method' => $request->getMethod(),
            'isLoginRoute' => $isLoginRoute,
            'isPostMethod' => $isPostMethod,
        ]);

        return $isLoginRoute && $isPostMethod;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        if (empty($email) || empty($password)) {
            $this->logger->error('Email or password missing.');
            throw new \InvalidArgumentException('Email or password missing.');
        }

        $this->logger->info("Attempting to authenticate user with email: $email");

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]) ??
                        $this->employeeRepository->findOneBy(['email' => $userIdentifier]);

                if (!$user) {
                    $this->logger->error("User not found for email: $userIdentifier");
                    throw new UserNotFoundException("User not found for email: $userIdentifier");
                }

                $this->logger->info("User found for email: $userIdentifier", [
                    'roles' => $user->getRoles()
                ]);

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Méthode vide pour permettre la gestion de redirection par l'Event Listener
        return null;
    }
}