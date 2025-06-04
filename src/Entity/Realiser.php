<?php

namespace App\Entity;

use App\Repository\RealiserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealiserRepository::class)]
class Realiser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Vol::class)]
    #[ORM\JoinColumn(name: "vol_id", referencedColumnName: "id")]
    private $vol;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Pilote::class)]
    #[ORM\JoinColumn(name: "matriculePilote", referencedColumnName: "matricule")]
    private $pilote;

    public function getVol(): ?Vol { 
        return $this->vol; 
    }
    
    public function setVol(?Vol $vol): self { 
        $this->vol = $vol; return $this; 
    }

    public function getPilote(): ?Pilote { 
        return $this->pilote; 
    }

    public function setPilote(?Pilote $pilote): self { 
        $this->pilote = $pilote; return $this; 
    }
}
