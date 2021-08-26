<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id; 

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="types")
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image;

    public function __construct()
    {
        $this->Title = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Book[]
     */
    public function getTitle(): Collection
    {
        return $this->Title;
    }

    public function addTitle(Book $title): self
    {
        if (!$this->Title->contains($title)) {
            $this->Title[] = $title;
        }

        return $this;
    }

    public function removeTitle(Book $title): self
    {
        $this->Title->removeElement($title);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getImage()
    {
        return $this->Image;
    }

    public function setImage($Image)
    {
        if ($Image != null) {
            $this->Image = $Image;
        }
        $this->Image = $Image;

        return $this;
    }
}
