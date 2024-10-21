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

    public function __construct()
    {
        $this->date = new \DateTime(); // Par défaut la date actuelle
        $this->status = 'New'; // Par défaut le statut de la commande est "New"
        $this->cartJeuxVideos = new ArrayCollection();
    }

    // Getter et Setter pour $id
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter et Setter pour $user
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // Getter et Setter pour $date
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    // Getter et Setter pour $total
    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    // Getter et Setter pour $status
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    // Getter et Setter pour $agence
    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    // Gestion des relations avec CartJeuxVideos
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
}