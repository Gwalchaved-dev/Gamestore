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
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Méthode pour vérifier si l'authentificateur doit gérer cette requête
     */
    public function supports(Request $request): bool
    {
        // Vérifie que la route est bien celle du login et que la méthode est POST
        return $request->attributes->get('_route') === self::LOGIN_ROUTE
            && $request->isMethod('POST');
    }

    /**
     * Récupération des informations de connexion et construction du Passport pour l'authentification
     */
    public function authenticate(Request $request): Passport
    {
        // Récupère l'email et le mot de passe depuis le formulaire POST
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Enregistre l'email dans la session pour réafficher le dernier utilisateur
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email), // Récupère l'utilisateur en fonction de l'email
            new PasswordCredentials($password), // Vérifie les identifiants
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')), // Protection CSRF
                new RememberMeBadge(), // Option "se souvenir de moi"
            ]
        );
    }

    /**
     * Gestion de la redirection après un succès d'authentification
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si une page cible était stockée, y rediriger l'utilisateur
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Rediriger selon le rôle de l'utilisateur après la connexion
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            // Rediriger l'administrateur vers la page d'administration
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }

        // Redirige par défaut vers la page d'accueil après la connexion
        return new RedirectResponse($this->urlGenerator->generate('app_homepage'));
    }

    /**
     * Définit l'URL de la page de connexion
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}