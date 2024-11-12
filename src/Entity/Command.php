<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')] 
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $total = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    #[ORM\ManyToOne(targetEntity: Agence::class)] 
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: CartJeuxVideos::class, cascade: ['persist', 'remove'])]
    private Collection $cartJeuxVideos;

    // Nouvelles colonnes pour le titre, la quantité et le genre
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $genre = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->status = 'New';
        $this->cartJeuxVideos = new ArrayCollection();
        $this->quantite = 0; // Initialisation de la quantité
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;
        return $this;
    }

    public function getCartJeuxVideos(): Collection
    {
        return $this->cartJeuxVideos;
    }

    public function addCartJeuxVideo(CartJeuxVideos $cartJeuxVideo): self
    {
        if (!$this->cartJeuxVideos->contains($cartJeuxVideo)) {
            $this->cartJeuxVideos[] = $cartJeuxVideo;
            $cartJeuxVideo->setCommand($this);
        }
        return $this;
    }

    public function removeCartJeuxVideo(CartJeuxVideos $cartJeuxVideo): self
    {
        if ($this->cartJeuxVideos->removeElement($cartJeuxVideo)) {
            if ($cartJeuxVideo->getCommand() === $this) {
                $cartJeuxVideo->setCommand(null);
            }
        }
        return $this;
    }

    // Getters et Setters pour les nouvelles colonnes
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    /**
     * Retourne les titres, genres et quantités pour chaque jeu dans la commande.
     *
     * @return array
     */
    public function getGamesTitlesAndQuantities(): array
    {
        $gamesData = [];
        foreach ($this->cartJeuxVideos as $cartJeuxVideo) {
            $jeuxVideo = $cartJeuxVideo->getJeuxVideo();
            if ($jeuxVideo) {
                $gamesData[] = [
                    'titre' => $jeuxVideo->getTitre(),
                    'genre' => $jeuxVideo->getGenre() ?? 'Inconnu', // Gérer le cas où le genre est null
                    'quantite' => $cartJeuxVideo->getQuantite()
                ];
            }
        }
        return $gamesData;
    }
}