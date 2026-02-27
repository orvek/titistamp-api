<?php

namespace App\Entity;

use App\Repository\CommerceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CommerceRepository::class)]
class Commerce
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lng = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Stamp>
     */
    #[ORM\OneToMany(targetEntity: Stamp::class, mappedBy: 'commerce')]
    private Collection $stamps;

    #[ORM\ManyToOne(inversedBy: 'commerces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 255)]
    private ?string $stamp = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'commerce')]
    private Collection $tickets;

    #[ORM\ManyToOne(inversedBy: 'commerces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommerceCategory $category = null;

    public function __construct()
    {
        $this->stamps = new ArrayCollection();
        $this->active = true;
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
            $stamp->setCommerce($this);
        }

        return $this;
    }

    public function removeStamp(Stamp $stamp): static
    {
        if ($this->stamps->removeElement($stamp)) {
            // set the owning side to null (unless already changed)
            if ($stamp->getCommerce() === $this) {
                $stamp->setCommerce(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStamp(): ?string
    {
        return $this->stamp;
    }

    public function setStamp(string $stamp): static
    {
        $this->stamp = $stamp;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setCommerce($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getCommerce() === $this) {
                $ticket->setCommerce(null);
            }
        }

        return $this;
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

    public function getCategory(): ?CommerceCategory
    {
        return $this->category;
    }

    public function setCategory(?CommerceCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
    
}
