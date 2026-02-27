<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Stamp>
     */
    #[ORM\OneToMany(targetEntity: Stamp::class, mappedBy: 'user')]
    private Collection $stamps;

    /**
     * @var Collection<int, Commerce>
     */
    #[ORM\OneToMany(targetEntity: Commerce::class, mappedBy: 'owner')]
    private Collection $commerces;

    /**
     * @var Collection<int, Swap>
     */
    #[ORM\OneToMany(targetEntity: Swap::class, mappedBy: 'user')]
    private Collection $swaps;

    public function __construct()
    { 
        $this->active = true;
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->stamps = new ArrayCollection();
        $this->commerces = new ArrayCollection();
        $this->swaps = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
    

    public function getIdAsString(): string
    {
        return $this->id?->toRfc4122();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = "ROLE_TEAM";

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * ISO 8601 (DATE_ATOM)
     */
    public function getCreatedAtFormatted(): ?string
    {
        return $this->createdAt?->format(DATE_ATOM);
    }

    /**
     * ISO 8601 (DATE_ATOM)
     */
    public function getUpdatedAtFormatted(): ?string
    {
        return $this->updatedAt?->format(DATE_ATOM);
    }

    /**
     * @return Collection<int, Stamp>
     */
    public function getStamps(): Collection
    {
        return $this->stamps;
    }

    public function addStamp(Stamp $stamp): static
    {
        if (!$this->stamps->contains($stamp)) {
            $this->stamps->add($stamp);
            $stamp->setUser($this);
        }

        return $this;
    }

    public function removeStamp(Stamp $stamp): static
    {
        if ($this->stamps->removeElement($stamp)) {
            // set the owning side to null (unless already changed)
            if ($stamp->getUser() === $this) {
                $stamp->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commerce>
     */
    public function getCommerces(): Collection
    {
        return $this->commerces;
    }

    public function addCommerce(Commerce $commerce): static
    {
        if (!$this->commerces->contains($commerce)) {
            $this->commerces->add($commerce);
            $commerce->setOwner($this);
        }

        return $this;
    }

    public function removeCommerce(Commerce $commerce): static
    {
        if ($this->commerces->removeElement($commerce)) {
            // set the owning side to null (unless already changed)
            if ($commerce->getOwner() === $this) {
                $commerce->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Swap>
     */
    public function getSwaps(): Collection
    {
        return $this->swaps;
    }

    public function addSwap(Swap $swap): static
    {
        if (!$this->swaps->contains($swap)) {
            $this->swaps->add($swap);
            $swap->setUser($this);
        }

        return $this;
    }

    public function removeSwap(Swap $swap): static
    {
        if ($this->swaps->removeElement($swap)) {
            // set the owning side to null (unless already changed)
            if ($swap->getUser() === $this) {
                $swap->setUser(null);
            }
        }

        return $this;
    }

}
