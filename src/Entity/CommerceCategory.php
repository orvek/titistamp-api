<?php

namespace App\Entity;

use App\Repository\CommerceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CommerceCategoryRepository::class)]
class CommerceCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, Commerce>
     */
    #[ORM\OneToMany(targetEntity: Commerce::class, mappedBy: 'category')]
    private Collection $commerces;

    public function __construct()
    {
        $this->commerces = new ArrayCollection();
        $this->active = true;
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
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
            $commerce->setCategory($this);
        }

        return $this;
    }

    public function removeCommerce(Commerce $commerce): static
    {
        if ($this->commerces->removeElement($commerce)) {
            // set the owning side to null (unless already changed)
            if ($commerce->getCategory() === $this) {
                $commerce->setCategory(null);
            }
        }

        return $this;
    }
}
