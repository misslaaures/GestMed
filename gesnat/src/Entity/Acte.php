<?php

namespace App\Entity;

use App\Repository\ActeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActeRepository::class)
 */
class Acte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $libact;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixact;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibact(): ?string
    {
        return $this->libact;
    }

    public function setLibact(string $libact): self
    {
        $this->libact = $libact;

        return $this;
    }

    public function getPrixact(): ?int
    {
        return $this->prixact;
    }

    public function setPrixact(int $prixact): self
    {
        $this->prixact = $prixact;

        return $this;
    }

    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(\DateTimeInterface $datemodif): self
    {
        $this->datemodif = $datemodif;

        return $this;
    }
}
