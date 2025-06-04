<?php 

namespace App\Entity;

use App\Repository\HangarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HangarRepository::class)]
class Hangar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'integer')]
    private $capacite;

    #[ORM\ManyToOne(targetEntity: Aeroport::class, inversedBy: 'hangars')]
    #[ORM\JoinColumn(name: 'aeroport_id', referencedColumnName: 'id')]
    private ?Aeroport $aeroport = null;
    

    public function getId(): ?int { 
        return $this->id; 
    }
    
    public function getNom(): ?string { 
        return $this->nom; 
    }

    public function setNom(string $nom): self { 
        $this->nom = $nom; return $this; 
    }

    public function getCapacite(): ?int { 
        return $this->capacite; 
    }

    public function setCapacite(int $capacite): self { 
        $this->capacite = $capacite; return $this; 
    }

    public function getAeroport(): ?Aeroport { 
        return $this->aeroport; 
    }

    public function setAeroport(?Aeroport $aeroport): self { 
        $this->aeroport = $aeroport; return $this; 
    }
}
