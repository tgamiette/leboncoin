<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Offer::class, inversedBy: 'tags')]
    private Collection $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Offer $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name->add($name);
        }

        return $this;
    }

    public function removeName(Offer $name): self
    {
        $this->name->removeElement($name);

        return $this;
    }
}
