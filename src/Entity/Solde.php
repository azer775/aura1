<?php

namespace App\Entity;

use App\Repository\SoldeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;




#[ORM\Entity(repositoryClass: SoldeRepository::class)]
class Solde
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   /**
     *  @Groups({"post:read"})
    */
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive(message:"le montant doit etre positif")]
   /**
     *  @Groups({"post:read"})
    */
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
   /**
     *  @Groups({"post:read"})
    */
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'soldes',)]
   /**
     *  @Groups({"post:read"})
    */
    private ?Terrain $id_terrain = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdTerrain(): ?Terrain
    {
        return $this->id_terrain;
    }

    public function setIdTerrain(?Terrain $id_terrain): self
    {
        $this->id_terrain = $id_terrain;

        return $this;
    }
}
