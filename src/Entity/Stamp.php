<?php

namespace App\Entity;

use App\Repository\StampRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StampRepository::class)]
class Stamp
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'stamps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'stamps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commerce $commerce = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->active = true;
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCommerce(): ?Commerce
    {
        return $this->commerce;
    }

    public function setCommerce(?Commerce $commerce): static
    {
        $this->commerce = $commerce;

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

    public function setCreatedAt(?\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
}
