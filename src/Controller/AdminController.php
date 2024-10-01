<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeFormType;
use App\Repository\EmployeeRepository; // Utilisation du repository Employee
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function manageEmployees(Request $request, EntityManagerInterface $em, EmployeeRepository $employeeRepository): Response
    {
        // Création du formulaire d'ajout d'employé
        $employee = new Employee();
        $form = $this->createForm(EmployeeFormType::class, $employee);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir le rôle d'employé
            $employee->setRoles(['ROLE_EMPLOYEE']);
            $employee->setCreatedAt(new \DateTime()); // Ajouter la date de création

            // Sauvegarder l'employé dans la base de données
            $em->persist($employee);
            $em->flush();

            // Ajouter un message flash pour confirmer l'ajout
            $this->addFlash('success', 'Employé ajouté avec succès.');
        }

        // Récupérer tous les employés pour les afficher dans le tableau
        $employees = $employeeRepository->findAll();

        // Rendre la vue avec le formulaire et la liste des employés
        return $this->render('admin/admin_employee.html.twig', [
            'form' => $form->createView(),
            'employees' => $employees, // Renommer en 'employees' pour plus de clarté
        ]);
    }
}
