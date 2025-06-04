<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateHeureDepart;

    #[ORM\Column(type: 'datetime')]
    private $dateHeureArrivee;

    #[ORM\ManyToOne(targetEntity: Avion::class)]
    #[ORM\JoinColumn(name: "avion_id", referencedColumnName: "id")]
    private $avion;

    #[ORM\ManyToOne(targetEntity: Aeroport::class, inversedBy: 'volsDepart')]
    #[ORM\JoinColumn(name: 'aeroport_depart_id', referencedColumnName: 'id', nullable: false)]
    private ?Aeroport $aeroportDepart = null;

    #[ORM\ManyToOne(targetEntity: Aeroport::class, inversedBy: 'volsArrivee')]
    #[ORM\JoinColumn(name: 'aeroport_arrivee_id', referencedColumnName: 'id', nullable: false)]
    private ?Aeroport $aeroportArrivee = null;

    #[ORM\ManyToOne(targetEntity: Pilote::class)]
    #[ORM\JoinColumn(name: "pilote_id", referencedColumnName: "matricule")]
    private $pilote;

    #[ORM\Column]
    private ?\DateTime $date_heure_depart_effective = null;

    #[ORM\Column]
    private ?\DateTime $date_heure_arrivee_effective = null;

    public function getId(): ?int { return $this->id; }

    public function getDateHeureDepart(): ?\DateTimeInterface { 
        return $this->dateHeureDepart; 
    }

    public function setDateHeureDepart(\DateTimeInterface $v): self { 
        $this->dateHeureDepart = $v; 
        return $this; 
    }
    
    public function getDateHeureArrivee(): ?\DateTimeInterface { 
        return $this->dateHeureArrivee; 
    }
    
    public function setDateHeureArrivee(\DateTimeInterface $v): self { 
        $this->dateHeureArrivee = $v; 
        return $this; 
    }
    
    public function getAvion(): ?Avion { 
        return $this->avion; 
    }
    
    public function setAvion(?Avion $v): self { 
        $this->avion = $v; 
        return $this; 
    }
    
    public function getAeroportDepart(): ?Aeroport { 
        return $this->aeroportDepart; 
    }
    
    public function setAeroportDepart(?Aeroport $v): self { $this->aeroportDepart = $v; 
        return $this; 
    }
    
    public function getAeroportArrivee(): ?Aeroport { 
        return $this->aeroportArrivee; 
    }
    
    public function setAeroportArrivee(?Aeroport $v): self { $this->aeroportArrivee = $v; 
        return $this; 
    }
    
    public function getPilote(): ?Pilote { 
        return $this->pilote; 
    }
    
    public function setPilote(?Pilote $p): self  { $this->pilote = $p; 
        return $this; 
    }

    public function getDateHeureDepartEffective(): ?\DateTime
    {
        return $this->date_heure_depart_effective;
    }

    public function setDateHeureDepartEffective(\DateTime $date_heure_depart_effective): static
    {
        $this->date_heure_depart_effective = $date_heure_depart_effective;

        return $this;
    }

    public function getDateHeureArriveeEffective(): ?\DateTime
    {
        return $this->date_heure_arrivee_effective;
    }

    public function setDateHeureArriveeEffective(\DateTime $date_heure_arrivee_effective): static
    {
        $this->date_heure_arrivee_effective = $date_heure_arrivee_effective;

        return $this;
    }
    
}
