<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_Don = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Membre $id_Membre = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Association $id_Assoc = null;

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

    public function getDateDon(): ?\DateTimeInterface
    {
        return $this->date_Don;
    }

    public function setDateDon(\DateTimeInterface $date_Don): self
    {
        $this->date_Don = $date_Don;

        return $this;
    }

    public function getIdMembre(): ?Membre
    {
        return $this->id_Membre;
    }

    public function setIdMembre(?Membre $id_Membre): self
    {
        $this->id_Membre = $id_Membre;

        return $this;
    }

    public function getIdAssoc(): ?Association
    {
        return $this->id_Assoc;
    }

    public function setIdAssoc(?Association $id_Assoc): self
    {
        $this->id_Assoc = $id_Assoc;

        return $this;
    }
}
