<?php

namespace App\Entity;

use App\Repository\GererRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GererRepository::class)]
class Gerer
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")] 
    private $utilisateur;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Vol::class)]
    #[ORM\JoinColumn(name: "vol_id", referencedColumnName: "id")]
    private $vol;

    public function getUtilisateur(): ?Utilisateur { 
        return $this->utilisateur; 
    }
    
    public function setUtilisateur(?Utilisateur $utilisateur): self {
        $this->utilisateur = $utilisateur; 
        return $this; 
    
    }
    public function getVol(): ?Vol {
        return $this->vol; 
    }
    
    public function setVol(?Vol $vol): self {
        $this->vol = $vol; return $this; 
    }
}
