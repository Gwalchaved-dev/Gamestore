<?php

namespace App\Controller;

use App\Entity\JeuxVideos;
use App\Form\JeuxVideosFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminJeuxController extends AbstractController
{
    #[Route('/admin/jeux/ajouter', name: 'admin_ajouter_jeu')]
    public function ajouterJeu(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $jeu = new JeuxVideos();
        $form = $this->createForm(JeuxVideosFormType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'image uploadée
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Déplace l'image dans le répertoire configuré
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                // Stocke le nom du fichier d'image
                $jeu->setImage($newFilename);
            }

            $entityManager->persist($jeu);
            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été ajouté.');

            return $this->redirectToRoute('admin_jeux');
        }

        return $this->render('admin/ajouter_jeu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/jeux/modifier/{id}', name: 'admin_modifier_jeu')]
    public function modifierJeu(Request $request, JeuxVideos $jeu, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(JeuxVideosFormType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'image uploadée si elle a été modifiée
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $jeu->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été modifié.');

            return $this->redirectToRoute('admin_jeux');
        }

        return $this->render('admin/modifier_jeu.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
