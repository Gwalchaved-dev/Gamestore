<?php

namespace App\Entity;

use App\Repository\JeuxImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JeuxImagesRepository::class)]
class JeuxImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255)]
    private ?string $altText = null;

    #[ORM\ManyToOne(inversedBy: 'jeuxImages')]
    private ?JeuxVideos $jeu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): static
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
}
