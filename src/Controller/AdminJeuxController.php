<?php

namespace App\Controller;

use App\Entity\JeuxVideos;
use App\Form\JeuxVideosFormType;
use App\Repository\JeuxVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminJeuxController extends AbstractController
{
    #[Route('/admin/jeux/stock', name: 'admin_jeux_stock')]
    public function afficherStock(JeuxVideosRepository $jeuxRepository): Response
    {
        // Récupérer tous les jeux vidéo dans la base de données
        $jeux = $jeuxRepository->findAll();

        // Renvoyer la vue avec les jeux récupérés
        return $this->render('admin/stock.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/admin/jeux/ajouter', name: 'admin_ajouter_jeu')]
    public function ajouterJeu(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $jeu = new JeuxVideos();
        $form = $this->createForm(JeuxVideosFormType::class, $jeu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'image principale
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = $this->handleImageUpload($imageFile, $slugger);
                if (!$newFilename) {
                    $this->addFlash('error', 'L\'image principale est obligatoire.');
                    return $this->render('admin/ajouter_jeu.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                $jeu->setImage($newFilename); // Stocker le nom du fichier dans l'entité
            } else {
                $this->addFlash('error', 'L\'image principale est obligatoire.');
                return $this->render('admin/ajouter_jeu.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Gérer la deuxième image
            $secondImageFile = $form->get('secondImage')->getData();
            if ($secondImageFile) {
                $secondImageFilename = $this->handleImageUpload($secondImageFile, $slugger);
                if ($secondImageFilename) {
                    $jeu->setSecondImage($secondImageFilename);
                } else {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de la deuxième image.');
                }
            }

            // Gérer la troisième image
            $thirdImageFile = $form->get('thirdImage')->getData();
            if ($thirdImageFile) {
                $thirdImageFilename = $this->handleImageUpload($thirdImageFile, $slugger);
                if ($thirdImageFilename) {
                    $jeu->setThirdImage($thirdImageFilename);
                } else {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de la troisième image.');
                }
            }

            $entityManager->persist($jeu);
            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été ajouté avec ses images.');

            return $this->redirectToRoute('admin_jeux_stock');
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
            // Gérer l'image principale
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = $this->handleImageUpload($imageFile, $slugger);
                if ($newFilename) {
                    $jeu->setImage($newFilename);
                }
            }

            // Gérer la deuxième image
            $secondImageFile = $form->get('secondImage')->getData();
            if ($secondImageFile) {
                $secondImageFilename = $this->handleImageUpload($secondImageFile, $slugger);
                if ($secondImageFilename) {
                    $jeu->setSecondImage($secondImageFilename);
                }
            }

            // Gérer la troisième image
            $thirdImageFile = $form->get('thirdImage')->getData();
            if ($thirdImageFile) {
                $thirdImageFilename = $this->handleImageUpload($thirdImageFile, $slugger);
                if ($thirdImageFilename) {
                    $jeu->setThirdImage($thirdImageFilename);
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été modifié avec ses images.');

            return $this->redirectToRoute('admin_jeux_stock');
        }

        return $this->render('admin/modifier_jeu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Gérer l'upload de l'image pour un jeu.
     */
    private function handleImageUpload($imageFile, SluggerInterface $slugger): ?string
    {
        // Vérifie que l'image est bien un fichier UploadedFile
        if ($imageFile instanceof UploadedFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                // Déplace l'image dans le répertoire configuré
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                return $newFilename;
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                return null;
            }
        }

        return null;
    }
}