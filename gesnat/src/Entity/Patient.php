<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient
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
    private $nompat;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sexe;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaispat;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fonctionpat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adressepat;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $telephonepat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodif;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Consultation", mappedBy="patient")
     */
    private $consultations;

    /**
     * @ORM\OneToMany(targetEntity=Consulter::class, mappedBy="patient")
     */
    private $consulters;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="patient")
     */
    private $factures;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
        $this->consulters = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNompat(): ?string
    {
        return $this->nompat;
    }

    public function setNompat(string $nompat): self
    {
        $this->nompat = $nompat;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDatenaispat(): ?\DateTimeInterface
    {
        return $this->datenaispat;
    }

    public function setDatenaispat(\DateTimeInterface $datenaispat): self
    {
        $this->datenaispat = $datenaispat;

        return $this;
    }

    public function getFonctionpat(): ?string
    {
        return $this->fonctionpat;
    }

    public function setFonctionpat(string $fonctionpat): self
    {
        $this->fonctionpat = $fonctionpat;

        return $this;
    }

    public function getAdressepat(): ?string
    {
        return $this->adressepat;
    }

    public function setAdressepat(string $adressepat): self
    {
        $this->adressepat = $adressepat;

        return $this;
    }

    public function getTelephonepat(): ?string
    {
        return $this->telephonepat;
    }

    public function setTelephonepat(string $telephonepat): self
    {
        $this->telephonepat = $telephonepat;

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
     * @return Collection|Consultation[]
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setPatient($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->contains($consultation)) {
            $this->consultations->removeElement($consultation);
            // set the owning side to null (unless already changed)
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->nompat;
        // to show the id of the Category in the select
        return $this->id;
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
            $consulter->setPatient($this);
        }

        return $this;
    }

    public function removeConsulter(Consulter $consulter): self
    {
        if ($this->consulters->contains($consulter)) {
            $this->consulters->removeElement($consulter);
            // set the owning side to null (unless already changed)
            if ($consulter->getPatient() === $this) {
                $consulter->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setPatient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->contains($facture)) {
            $this->factures->removeElement($facture);
            // set the owning side to null (unless already changed)
            if ($facture->getPatient() === $this) {
                $facture->setPatient(null);
            }
        }

        return $this;
    }
    

}
