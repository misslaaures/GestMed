<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFact;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="float")
     */
    private $mtt;

   

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFact(): ?\DateTimeInterface
    {
        return $this->dateFact;
    }

    public function setDateFact(\DateTimeInterface $dateFact): self
    {
        $this->dateFact = $dateFact;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getMtt(): ?float
    {
        return $this->mtt;
    }

    public function setMtt(float $mtt): self
    {
        $this->mtt = $mtt;

        return $this;
    }

    
}
