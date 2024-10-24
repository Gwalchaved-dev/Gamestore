<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
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
use App\Repository\EmployeeRepository; // Ajout du repository Employee
use Psr\Log\LoggerInterface;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private Security $security;
    private LoggerInterface $logger;
    private UserRepository $userRepository;
    private EmployeeRepository $employeeRepository; // Ajout du repository Employee

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Security $security,
        LoggerInterface $logger,
        UserRepository $userRepository,
        EmployeeRepository $employeeRepository // Ajout du repository Employee
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->employeeRepository = $employeeRepository; // Initialisation
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === self::LOGIN_ROUTE
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Email ou mot de passe manquant.');
        }

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                // Cherche d'abord l'utilisateur dans UserRepository
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);

                // Si pas trouvé, cherche dans EmployeeRepository
                if (!$user) {
                    $user = $this->employeeRepository->findOneBy(['email' => $userIdentifier]);
                }

                // Retourner l'utilisateur trouvé ou null
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        $roles = $user->getRoles();

        // Log les rôles pour débogage
        $this->logger->info('User authenticated with roles: ' . implode(', ', $roles));

        // Redirige les administrateurs vers leur espace admin
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        // Redirige les employés vers leur espace employé
        if (in_array('ROLE_EMPLOYEE', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_employee'));
        }

        // Redirection par défaut vers la page d'accueil
        return new RedirectResponse($this->urlGenerator->generate('app_homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}