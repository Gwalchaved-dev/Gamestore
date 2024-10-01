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

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function supports(Request $request): bool
    {
        // Vérifie si la requête est liée à la route de connexion et si elle est de type POST
        return $request->attributes->get('_route') === self::LOGIN_ROUTE
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        // Vérification si l'email ou le mot de passe sont vides, peut-être utile pour le débogage
        if (empty($email) || empty($password)) {
            throw new \InvalidArgumentException('Email ou mot de passe manquant.');
        }

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirection personnalisée en fonction du rôle de l'utilisateur
        $user = $token->getUser();

        // Si l'utilisateur est un admin, redirige vers l'espace admin
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        // Sinon, redirige vers la page d'accueil par défaut
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_homepage'));
    }

    protected function getLoginUrl(Request $request): string
    {
        // Génère l'URL de la page de connexion
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}