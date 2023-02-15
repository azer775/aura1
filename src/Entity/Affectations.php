<?php

namespace App\Entity;

use App\Repository\AffectationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationsRepository::class)]
class Affectations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Technicien $technicien_id = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Terrain $terrain_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getTechnicienId(): ?Technicien
    {
        return $this->technicien_id;
    }

    public function setTechnicienId(?Technicien $technicien_id): self
    {
        $this->technicien_id = $technicien_id;

        return $this;
    }

    public function getTerrainId(): ?Terrain
    {
        return $this->terrain_id;
    }

    public function setTerrainId(?Terrain $terrain_id): self
    {
        $this->terrain_id = $terrain_id;

        return $this;
    }
}
