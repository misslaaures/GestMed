<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
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
    private $dateconsult;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $examen;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $diagnostic;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Praticien", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $praticien;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodif;

    /**
     * @ORM\OneToMany(targetEntity=Consulter::class, mappedBy="consultation", orphanRemoval=true)
     */
    private $consulters;

    public function __construct()
    {
        $this->consulters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateconsult(): ?\DateTimeInterface
    {
        return $this->dateconsult;
    }

    public function setDateconsult(\DateTimeInterface $dateconsult): self
    {
        $this->dateconsult = $dateconsult;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getExamen(): ?string
    {
        return $this->examen;
    }

    public function setExamen(?string $examen): self
    {
        $this->examen = $examen;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

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

    public function getPraticien(): ?Praticien
    {
        return $this->praticien;
    }

    public function setPraticien(?Praticien $praticien): self
    {
        $this->praticien = $praticien;

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

    /**
     * @return Collection|Consulter[]
     */
    public function getConsulters(): Collection
    {
        return $this->consulters;
    }

    public function addConsulter(Consulter $consulter): self
    {
        if (!$this->consulters->contains($consulter)) {
            $this->consulters[] = $consulter;
            $consulter->setConsultation($this);
        }

        return $this;
    }

    public function removeConsulter(Consulter $consulter): self
    {
        if ($this->consulters->contains($consulter)) {
            $this->consulters->removeElement($consulter);
            // set the owning side to null (unless already changed)
            if ($consulter->getConsultation() === $this) {
                $consulter->setConsultation(null);
            }
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->motif;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
