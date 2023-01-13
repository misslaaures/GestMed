<?php

namespace App\Entity;

use App\Repository\FacturationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturationRepository::class)
 */
class Facturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idfacture;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="facturations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $qteP;

    /**
     * @ORM\Column(type="float")
     */
    private $mt;

    /**
     * @ORM\Column(type="float")
     */
    private $rem;

    /**
     * @ORM\Column(type="float")
     */
    private $mtr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdfacture(): ?int
    {
        return $this->idfacture;
    }

    public function setIdfacture(int $idfacture): self
    {
        $this->idfacture = $idfacture;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQteP(): ?int
    {
        return $this->qteP;
    }

    public function setQteP(int $qteP): self
    {
        $this->qteP = $qteP;

        return $this;
    }

    public function getMt(): ?float
    {
        return $this->mt;
    }

    public function setMt(float $mt): self
    {
        $this->mt = $mt;

        return $this;
    }

    public function getRem(): ?float
    {
        return $this->rem;
    }

    public function setRem(float $rem): self
    {
        $this->rem = $rem;

        return $this;
    }

    public function getMtr(): ?float
    {
        return $this->mtr;
    }

    public function setMtr(float $mtr): self
    {
        $this->mtr = $mtr;

        return $this;
    }
}
