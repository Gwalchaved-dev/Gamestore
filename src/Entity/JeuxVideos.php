<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\JeuxVideosRepository;

#[ORM\Entity(repositoryClass: JeuxVideosRepository::class)]
class JeuxVideos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le titre ne doit pas être vide.")]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La description ne doit pas être vide.")]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\Positive(message: "Le prix doit être un nombre positif.")]
    private ?float $prix = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'image ne doit pas être vide.")]
    private ?string $image = null;

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
