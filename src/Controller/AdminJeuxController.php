<?php

namespace App\Controller;

use App\Entity\JeuxVideos;
use App\Entity\JeuxImages;
use App\Form\JeuxVideosFormType;
use App\Repository\JeuxVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

        // Vérification des données du formulaire avant soumission
        dump($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'image principale
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // On appelle la méthode pour gérer l'upload de l'image principale
                $newFilename = $this->handleImageUpload($imageFile, $jeu, $slugger);
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

            // Gérer les autres images
            $imageFiles = $form->get('images')->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $newFilename = $this->handleImageUpload($imageFile, $jeu, $slugger);
                    if ($newFilename) {
                        $image = new JeuxImages();
                        $image->setImagePath($newFilename);
                        $jeu->addImage($image); // Associer les images supplémentaires au jeu
                    }
                }
            }

            $entityManager->persist($jeu);
            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été ajouté avec ses images.');

            // Redirection vers la page d'affichage du stock après l'ajout d'un jeu
            return $this->redirectToRoute('admin_jeux_stock'); 
        } else {
            dump('Erreurs de validation détectées:');
            // Récupération et affichage détaillé des erreurs
            foreach ($form->getErrors(true) as $error) {
                dump('Champ : ' . $error->getOrigin()->getName());
                dump('Message : ' . $error->getMessage());
            }
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

        dump($form->getData()); // Vérification des données du formulaire après modification

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer les images uploadées si elles ont été modifiées
            $imageFiles = $form->get('images')->getData();
            if ($imageFiles) {
                foreach ($imageFiles as $imageFile) {
                    $newFilename = $this->handleImageUpload($imageFile, $jeu, $slugger);
                    if ($newFilename) {
                        $image = new JeuxImages();
                        $image->setImagePath($newFilename);
                        $jeu->addImage($image); // Ajouter les nouvelles images si nécessaire
                    }
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le jeu a bien été modifié avec ses images.');

            // Redirection vers la page d'affichage du stock après la modification d'un jeu
            return $this->redirectToRoute('admin_jeux_stock');
        } else {
            dump('Erreurs de validation détectées:');
            // Récupération et affichage détaillé des erreurs
            foreach ($form->getErrors(true) as $error) {
                dump('Champ : ' . $error->getOrigin()->getName());
                dump('Message : ' . $error->getMessage());
            }
        }

        return $this->render('admin/modifier_jeu.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Gérer l'upload de l'image pour un jeu.
     */
    private function handleImageUpload($imageFile, JeuxVideos $jeu, SluggerInterface $slugger): ?string
    {
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

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