<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Psr\Log\LoggerInterface;

class AccountController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/account', name: 'account')]
    public function index(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        $this->logger->info('Current user: ' . ($user ? $user->getUserIdentifier() : 'None'));
        $this->logger->info('User roles: ' . implode(', ', $user ? $user->getRoles() : []));

        if (!$user || !$user instanceof User) {
            $this->logger->info('Redirecting to login page');
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            $this->logger->info('Redirecting to employee space');
            return $this->redirectToRoute('app_employee');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $this->logger->info('Redirecting to admin space');
            return $this->redirectToRoute('app_admin');
        }

        $this->logger->info('Rendering account page');
        return $this->render('account/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/account/edit/{field}', name: 'account_edit')]
    public function editField(Request $request, string $field, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user || !$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $allowedFields = ['email', 'nom', 'prenom', 'adresse_postale', 'codepostal', 'ville'];
        
        if (!in_array($field, $allowedFields)) {
            $this->addFlash('error', 'Modification non autorisée.');
            return $this->redirectToRoute('account');
        }

        if ($request->isMethod('POST')) {
            $newValue = $request->request->get('new_value');

            switch ($field) {
                case 'email':
                    $user->setEmail($newValue);
                    break;
                case 'nom':
                    $user->setNom($newValue);
                    break;
                case 'prenom':
                    $user->setPrenom($newValue);
                    break;
                case 'adresse_postale':
                    $user->setAdressePostale($newValue);
                    break;
                case 'codepostal':
                    $user->setCodepostal($newValue);
                    break;
                case 'ville':
                    $user->setVille($newValue);
                    break;
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', ucfirst($field) . ' modifié avec succès.');
            return $this->redirectToRoute('account');
        }

        return $this->render('account/edit_field.html.twig', [
            'user' => $user,
            'field' => $field,
        ]);
    }

    #[Route('/account/change-password', name: 'change_password', methods: ['POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        if (!$user || !$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        if ($newPassword !== $confirmPassword) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            return $this->redirectToRoute('account');
        }

        if ($user instanceof PasswordAuthenticatedUserInterface) {
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe changé avec succès.');
        }

        return $this->redirectToRoute('account');
    }
}
