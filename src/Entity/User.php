<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le nom ne doit pas être vide.")]
    private string $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne doit pas être vide.")]
    private string $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'adresse postale ne doit pas être vide.")]
    private string $adressePostale;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank(message: "Le code postal ne doit pas être vide.")]
    private string $codePostal;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "La ville ne doit pas être vide.")]
    private string $ville;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'adresse e-mail ne doit pas être vide.")]
    #[Assert\Email(message: 'L\'adresse e-mail "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    // Champ non persisté, utilisé uniquement dans le formulaire pour la saisie du mot de passe en clair
    #[Assert\NotBlank(message: "Le mot de passe ne doit pas être vide.")]
    #[Assert\Length(min: 6, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères.")]
    private ?string $plainPassword = null;

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAdressePostale(): ?string
    {
        return $this->adressePostale;
    }

    public function setAdressePostale(string $adressePostale): static
    {
        $this->adressePostale = $adressePostale;
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Ajoute toujours ROLE_USER par défaut
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER'; // Toujours ajouter ROLE_USER si non présent
        }
        $this->roles = array_unique($roles);
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        if ($password) {
            $this->password = $password;
        }
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }
}