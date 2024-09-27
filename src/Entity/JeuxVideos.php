<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\JeuxVideosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    // Ce champ stockera le nom du fichier une fois téléchargé
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'jeu', targetEntity: JeuxImages::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

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

    // Cette méthode retourne le nom du fichier de l'image principale
    public function getImage(): ?string
    {
        return $this->image;
    }

    // Cette méthode est appelée après l'upload pour enregistrer le nom du fichier
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    // Gestion des images supplémentaires
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(JeuxImages $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setJeu($this);
        }

        return $this;
    }

    public function removeImage(JeuxImages $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getJeu() === $this) {
                $image->setJeu(null);
            }
        }

        return $this;
    }
}
