<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
        if (!$user || !$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Redirige les administrateurs vers la page admin
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin'); // Redirection vers la route de l'espace admin
        }

        // Création du formulaire à partir de RegistrationFormType
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist user changes
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Informations mises à jour avec succès.');
            return $this->redirectToRoute('account');
        }

        return $this->render('account/account.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/change-password', name: 'change_password', methods: ['POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user || !$user instanceof User) {
            return $this->json(['error' => 'User not logged in'], 403);
        }

        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        if ($newPassword !== $confirmPassword) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            return $this->redirectToRoute('account');
        }

        // Assurez-vous que $user implémente PasswordAuthenticatedUserInterface
        if ($user instanceof PasswordAuthenticatedUserInterface) {
            // Encodage et mise à jour du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        }

        // Persist the user with the new password
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Mot de passe changé avec succès.');
        return $this->redirectToRoute('account');
    }
}