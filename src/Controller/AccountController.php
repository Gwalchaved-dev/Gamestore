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
            return $this->redirectToRoute('app_admin');
        }

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

        // Liste des champs autorisés à être modifiés
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
                    $user->setAdressePostale($newValue);  // Modification ici pour utiliser `adresse_postale`
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

        // Assurez-vous que $user implémente PasswordAuthenticatedUserInterface
        if ($user instanceof PasswordAuthenticatedUserInterface) {
            // Encodage et mise à jour du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);

            // Persiste l'utilisateur avec le nouveau mot de passe
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe changé avec succès.');
        }

        return $this->redirectToRoute('account');
    }
}
