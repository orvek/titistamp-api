<?php

namespace App\Entity;

use App\Repository\SwapRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SwapRepository::class)]
class Swap
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'swaps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'swaps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ticket $ticket = null;

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

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): static
    {
        $this->ticket = $ticket;

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
