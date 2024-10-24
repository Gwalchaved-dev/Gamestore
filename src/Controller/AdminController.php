<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeFormType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/espace.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/employee', name: 'admin_employee')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageEmployees(
        Request $request, 
        EntityManagerInterface $em, 
        EmployeeRepository $employeeRepository, 
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // Création du formulaire d'ajout d'employé
        $employee = new Employee();
        $form = $this->createForm(EmployeeFormType::class, $employee);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir le rôle d'employé
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setCreatedAt(new \DateTime());

            // Gestion de l'email
            $email = $form->get('email')->getData();
            $employee->setEmail($email);

            // Vérifier si un mot de passe a été fourni
            if ($form->get('plainPassword')->getData()) {
                // Hacher et définir le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($employee, $form->get('plainPassword')->getData());
                $employee->setPassword($hashedPassword);
            } else {
                // Si aucun mot de passe n'a été fourni, ajouter une erreur
                $this->addFlash('error', 'Le mot de passe est requis.');
                return $this->redirectToRoute('admin_employee');
            }

            // Sauvegarder l'employé dans la base de données
            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Employé ajouté avec succès.');
            return $this->redirectToRoute('admin_employee');
        }

        // Récupérer tous les employés pour les afficher dans le tableau
        $employees = $employeeRepository->findAll();

        // Rendre la vue avec le formulaire et les employés
        return $this->render('admin/admin_employee.html.twig', [
            'form' => $form->createView(),
            'employees' => $employees,
        ]);
    }

    #[Route('/admin/employee/delete/{id}', name: 'admin_delete_employee')]
    public function deleteEmployee(int $id, EmployeeRepository $employeeRepository, EntityManagerInterface $em): Response
    {
        $employee = $employeeRepository->find($id);

        if ($employee) {
            $em->remove($employee);
            $em->flush();
            $this->addFlash('success', 'Employé supprimé.');
        }

        return $this->redirectToRoute('admin_employee');
    }

    #[Route('/admin/employee/edit/{id}', name: 'admin_edit_employee')]
    public function editEmployee(
        int $id, 
        Request $request, 
        EmployeeRepository $employeeRepository, 
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            $this->addFlash('error', 'Employé non trouvé.');
            return $this->redirectToRoute('admin_employee');
        }

        $form = $this->createForm(EmployeeFormType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'email
            $email = $form->get('email')->getData();
            $employee->setEmail($email);

            // Hash the password if it's being updated
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($employee, $form->get('plainPassword')->getData());
                $employee->setPassword($hashedPassword);
            }

            $em->flush();
            $this->addFlash('success', 'Employé modifié avec succès.');
            return $this->redirectToRoute('admin_employee');
        }

        // Ajouter la liste des employés pour l'affichage du tableau
        $employees = $employeeRepository->findAll();

        return $this->render('admin/admin_employee.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee ?? null,
            'employees' => $employees
        ]);
    }
}
