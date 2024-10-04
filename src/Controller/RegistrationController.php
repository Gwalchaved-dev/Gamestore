<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private TokenStorageInterface $tokenStorage;
    private RequestStack $requestStack;

    public function __construct(
        EmailVerifier $emailVerifier,
        TokenStorageInterface $tokenStorage, 
        RequestStack $requestStack
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Initialiser la variable $user
        $user = $this->getUser(); // Assurez-vous que cette méthode retourne un utilisateur ou null

        // Si l'utilisateur est déjà connecté
        if ($user instanceof User) {
            $this->addFlash('info', 'Vous êtes déjà connecté en tant que ' . $user->getEmail());
            return $this->redirectToRoute('app_homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification des mots de passe
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // Hachage du mot de passe
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // **Attribution du rôle ROLE_USER**
            $user->setRoles(['ROLE_USER']);

            // Persistance des données de l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Connexion automatique après l'inscription
            $token = new UsernamePasswordToken($user, $hashedPassword, ['main'], $user->getRoles());
            $this->tokenStorage->setToken($token);
            
            // Récupère la session via RequestStack
            $session = $this->requestStack->getSession();
            $session->set('_security_main', serialize($token));
            $session->save();

            // Envoi de l'email de confirmation
            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@example.com', 'Registration Bot'))
                ->to($user->getEmail())
                ->subject('Merci de confirmer votre adresse email')
                ->htmlTemplate('registration/confirmation_email.html.twig');

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);

            // Redirection après inscription et connexion
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse email a été vérifiée.');
        return $this->redirectToRoute('app_register');
    }
}