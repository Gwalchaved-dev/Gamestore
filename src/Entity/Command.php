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
        $this->date = new \DateTime();
        $this->status = 'New';
        $this->cartJeuxVideos = new ArrayCollection();
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

    // Nouvelle méthode pour obtenir tous les jeux associés à la commande
    public function getGames(): Collection
    {
        $games = new ArrayCollection();
        foreach ($this->cartJeuxVideos as $cartJeuxVideo) {
            $games->add($cartJeuxVideo->getJeuxVideo());
        }
        return $games;
    }

    // Nouvelle méthode pour définir les jeux associés à la commande
    public function setGames(array $games): self
    {
        foreach ($games as $game) {
            $cartJeuxVideo = new CartJeuxVideos();
            $cartJeuxVideo->setJeuxVideo($game);
            $this->addCartJeuxVideo($cartJeuxVideo);
        }
        return $this;
    }
}