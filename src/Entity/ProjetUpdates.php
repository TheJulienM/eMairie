<?php

namespace App\Entity;

use App\Repository\ProjetUpdatesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjetUpdatesRepository::class)
 */
class ProjetUpdates
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idProjet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProjet(): ?int
    {
        return $this->idProjet;
    }

    public function setIdProjet(int $idProjet): self
    {
        $this->idProjet = $idProjet;

        return $this;
    }
}
