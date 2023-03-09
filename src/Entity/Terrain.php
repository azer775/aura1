<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $surface = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $potentiel = null;

   /* #[ORM\ManyToOne(inversedBy: 'terrains')]
    private ?Partenaire $id_partenaire = null;*/

    #[ORM\OneToMany(mappedBy: 'id_terrain', targetEntity: Part::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $parts;

    #[ORM\OneToMany(mappedBy: 'terrain', targetEntity: Affectations::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $affectations;

    #[ORM\ManyToOne(inversedBy: 'terrains')]
    private ?Membre $Membre = null;

    public function __construct()
    {
        $this->parts = new ArrayCollection();
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPotentiel(): ?float
    {
        return $this->potentiel;
    }

    public function setPotentiel(float $potentiel): self
    {
        $this->potentiel = $potentiel;

        return $this;
    }

  /*  public function getIdPartenaire(): ?Partenaire
    {
        return $this->id_partenaire;
    }

    public function setIdPartenaire(?Partenaire $id_partenaire): self
    {
        $this->id_partenaire = $id_partenaire;

        return $this;
    }*/

    /**
     * @return Collection<int, Part>
     */
    public function getParts(): Collection
    {
        return $this->parts;
    }

    public function addPart(Part $part): self
    {
        if (!$this->parts->contains($part)) {
            $this->parts->add($part);
            $part->setIdTerrain($this);
        }

        return $this;
    }

    public function removePart(Part $part): self
    {
        if ($this->parts->removeElement($part)) {
            // set the owning side to null (unless already changed)
            if ($part->getIdTerrain() === $this) {
                $part->setIdTerrain(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectations $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setTerrain($this);
        }

        return $this;
    }

    public function removeAffectation(Affectations $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getTerrain() === $this) {
                $affectation->setTerrain(null);
            }
        }

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->Membre;
    }

    public function setMembre(?Membre $Membre): self
    {
        $this->Membre = $Membre;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }
}
