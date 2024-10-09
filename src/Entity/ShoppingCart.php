<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Relation OneToOne avec l'utilisateur
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'shoppingCart')]
    #[ORM\JoinColumn(nullable: false)] // Indiquer ici que l'utilisateur est requis
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    // Relation OneToMany avec CartJeuxVideos
    #[ORM\OneToMany(mappedBy: 'shoppingCart', targetEntity: CartJeuxVideos::class, cascade: ['persist', 'remove'])]
    private Collection $cartJeuxVideos;

    public function __construct()
    {
        $this->cartJeuxVideos = new ArrayCollection();
        $this->dateCreation = new \DateTime(); // Initialise la date de création à maintenant
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // Getters et Setters pour User
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // Assurer la relation bidirectionnelle
        $this->user = $user;
        if ($user && $user->getShoppingCart() !== $this) {
            $user->setShoppingCart($this);
        }
        return $this;
    }

    // Getters et Setters pour Date de Création
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    // Gestion des CartJeuxVideos (jeux vidéos dans le panier)
    public function getCartJeuxVideos(): Collection
    {
        return $this->cartJeuxVideos;
    }

    public function addCartJeuxVideo(CartJeuxVideos $cartJeuxVideo): static
    {
        if (!$this->cartJeuxVideos->contains($cartJeuxVideo)) {
            $this->cartJeuxVideos[] = $cartJeuxVideo;
            $cartJeuxVideo->setShoppingCart($this);
        }

        return $this;
    }

    public function removeCartJeuxVideo(CartJeuxVideos $cartJeuxVideo): static
    {
        if ($this->cartJeuxVideos->removeElement($cartJeuxVideo)) {
            // Si l'élément est retiré, on dissocie la relation
            if ($cartJeuxVideo->getShoppingCart() === $this) {
                $cartJeuxVideo->setShoppingCart(null);
            }
        }

        return $this;
    }

    // Nouvelle méthode pour vérifier si le panier est vide
    public function isEmpty(): bool
    {
        return $this->cartJeuxVideos->isEmpty();
    }
}
