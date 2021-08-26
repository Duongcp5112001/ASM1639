<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\JoinTable(name="order_book")
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="orders")
     */
    private $Name;

        

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Price;


    /**
     * @ORM\Column(type="integer")
     */
    private $Quantity;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $UserName;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders1")
     */
    private $UserPhone;

    public function __construct()
    {
        $this->Name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Book[]
     */
    public function getName(): Collection
    {
        return $this->Name;
    }

    public function addName(Book $name): self
    {
        if (!$this->Name->contains($name)) {
            $this->Name[] = $name;
        }

        return $this;
    }

    public function removeName(Book $name): self
    {
        $this->Name->removeElement($name);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getUserName(): ?User
    {
        return $this->UserName;
    }

    public function setUserName(?User $UserName): self
    {
        $this->UserName = $UserName;

        return $this;
    }

    public function getUserPhone(): ?User
    {
        return $this->UserPhone;
    }

    public function setUserPhone(?User $UserPhone): self
    {
        $this->UserPhone = $UserPhone;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }
}
