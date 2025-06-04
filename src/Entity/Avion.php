<?php

namespace App\Entity;

use App\Repository\AvionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvionRepository::class)]
class Avion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $modele;

    #[ORM\Column(name:'nb_places', type: 'integer')]
    private $nbPlaces;

    #[ORM\ManyToOne(targetEntity: Hangar::class)]
    #[ORM\JoinColumn(name: "hangar_id", referencedColumnName: "id", nullable: true)]
    private $hangar;

    public function getId(): ?int {
        return $this->id; 
    }
    
    public function getModele(): ?string { 
        return $this->modele; 
    }
    
    public function setModele(string $modele): self { 
        $this->modele = $modele; return $this; 
    }

    public function getNbPlaces(): ?int { 
        return $this->nbPlaces; 
    }

    public function setNbPlaces(int $nbPlaces): self {
        $this->nbPlaces = $nbPlaces; return $this; 
    }

    public function getHangar(): ?Hangar { 
        return $this->hangar; 
    }
    
    public function setHangar(?Hangar $hangar): self {
        $this->hangar = $hangar; return $this; 
    }
}
