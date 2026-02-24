<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Role::class, fetch: 'EAGER')]
    #[ORM\JoinTable(name: 'user_role')]
    private Collection $userRoles;

    #[ORM\Column]
    private ?string $password = null;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Retorna os roles no formato exigido pelo Symfony Security (ROLE_*).
     * Sempre inclui ROLE_USER como mínimo.
     */
    public function getRoles(): array
    {
        $roles = $this->userRoles
            ->map(fn(Role $role) => $role->toSymfonyRole())
            ->toArray();

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(Role ...$roles): static
    {
        $this->userRoles = new ArrayCollection(array_values($roles));
        return $this;
    }

    public function addRole(Role $role): static
    {
        if (!$this->userRoles->contains($role)) {
            $this->userRoles->add($role);
        }
        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->userRoles->removeElement($role);
        return $this;
    }

    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}
}
