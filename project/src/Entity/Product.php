<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use App\Traits\LoggableEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    use LoggableEntityTrait;

    /**
     * @Groups({"customer", "product"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"customer", "product"})
     * @ORM\Column(type="string", length=10, nullable=true, unique=true)
     * @Assert\Regex("/^[0-9a-zA-Z]{4}\-[0-9a-zA-Z]{4}$/")
     */
    private $issn;


    /**
     * @Groups({"customer", "product"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"customer", "product"})
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @Groups({"product"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="products")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="uuid")
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssn(): ?string
    {
        return $this->issn;
    }

    public function setIssn(string $issn): self
    {
        $this->issn = $issn;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
