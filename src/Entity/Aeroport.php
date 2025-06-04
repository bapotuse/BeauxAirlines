<?php

namespace App\Entity;

use App\Repository\AeroportRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Hangar;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: AeroportRepository::class)]
class Aeroport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id", type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'string', length: 100)]
    private $pays;

    #[ORM\Column(type: 'string', length: 100)]
    private $ville;

    #[ORM\OneToMany(mappedBy: 'aeroport', targetEntity: Hangar::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $hangars;

    #[ORM\OneToMany(mappedBy: 'aeroportDepart', targetEntity: Vol::class)]
    private Collection $volsDepart;

    #[ORM\OneToMany(mappedBy: 'aeroportArrivee', targetEntity: Vol::class)]
    private Collection $volsArrivee;

    public function __construct()
    {
        $this->hangars = new ArrayCollection();
        $this->volsDepart = new ArrayCollection();
        $this->volsArrivee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getHangars(): Collection
    {
        return $this->hangars;
    }

    public function addHangar(Hangar $hangar): self
    {
        if (!$this->hangars->contains($hangar)) {
            $this->hangars[] = $hangar;
            $hangar->setAeroport($this);
        }

        return $this;
    }

    public function removeHangar(Hangar $hangar): self
    {
        if ($this->hangars->removeElement($hangar)) {
            if ($hangar->getAeroport() === $this) {
                $hangar->setAeroport(null);
            }
        }

        return $this;
    }

    public function getVolsDepart(): Collection
    {
        return $this->volsDepart;
    }

    public function addVolDepart(Vol $vol): self
    {
        if (!$this->volsDepart->contains($vol)) {
            $this->volsDepart[] = $vol;
            $vol->setAeroportDepart($this);
        }

        return $this;
    }

    public function removeVolDepart(Vol $vol): self
    {
        if ($this->volsDepart->removeElement($vol)) {
            if ($vol->getAeroportDepart() === $this) {
                $vol->setAeroportDepart(null);
            }
        }

        return $this;
    }

    public function getVolsArrivee(): Collection
    {
        return $this->volsArrivee;
    }

    public function addVolArrivee(Vol $vol): self
    {
        if (!$this->volsArrivee->contains($vol)) {
            $this->volsArrivee[] = $vol;
            $vol->setAeroportArrivee($this);
        }

        return $this;
    }

    public function removeVolArrivee(Vol $vol): self
    {
        if ($this->volsArrivee->removeElement($vol)) {
            if ($vol->getAeroportArrivee() === $this) {
                $vol->setAeroportArrivee(null);
            }
        }

        return $this;
    }

    
}
