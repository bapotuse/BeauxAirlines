<?php

namespace App\Entity;

use App\Repository\PiloteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiloteRepository::class)]
class Pilote
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private $matricule;

    #[ORM\Column(type: 'string', length: 100)]
    private $nom;

    #[ORM\Column(type: 'string', length: 100)]
    private $prenom;

    public function getMatricule(): ?int { 
        return $this->matricule; 
    }

    public function setMatricule(int $matricule): self { 
        $this->matricule = $matricule; return $this; 
    }
    
    public function getNom(): ?string { 
        return $this->nom; 
    }
    
    public function setNom(string $nom): self { 
        $this->nom = $nom; return $this; 
    }

    public function getPrenom(): ?string { 
        return $this->prenom; 
    }

    public function setPrenom(string $prenom): self { 
        $this->prenom = $prenom; return $this; 
    }
}
