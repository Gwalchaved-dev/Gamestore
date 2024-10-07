<?php

namespace App\Entity;

use App\Repository\JeuxImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JeuxImagesRepository::class)]
class JeuxImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $altText = null;

    #[ORM\ManyToOne(inversedBy: 'jeuxImages')]
    private ?JeuxVideos $jeu = null;

    /**
     * Propriété temporaire pour stocker le fichier UploadedFile.
     * Non persisté en base de données.
     */
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif'],
        mimeTypesMessage: 'Veuillez uploader une image valide (jpeg, png, gif).'
    )]
    private ?UploadedFile $imageFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getJeu(): ?JeuxVideos
    {
        return $this->jeu;
    }

    public function setJeu(?JeuxVideos $jeu): static
    {
        $this->jeu = $jeu;

        return $this;
    }

    /**
     * Retourne le fichier image temporaire.
     */
    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    /**
     * Définit le fichier image temporaire.
     */
    public function setImageFile(?UploadedFile $imageFile): static
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
