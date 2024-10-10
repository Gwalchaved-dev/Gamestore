<?php

namespace App\Entity;

use App\Repository\CommandRepository; // Changer pour le nouveau nom
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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')] // Changer 'orders' à 'commands'
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $total;

    #[ORM\Column(type: 'string')]
    private string $status;

    #[ORM\OneToMany(mappedBy: 'command', targetEntity: OrderItem::class, cascade: ['persist', 'remove'])] // Changer 'order' à 'command'
    private Collection $items;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->status = 'New';
        $this->items = new ArrayCollection();
    }

    // Getters et Setters
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

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getTotal(): float
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

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCommand($this); // Changer 'order' à 'command'
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // Set the owning side to null (unless already changed)
            if ($item->getCommand() === $this) { // Changer 'order' à 'command'
                $item->setCommand(null);
            }
        }

        return $this;
    }
}