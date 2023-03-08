<?php

namespace App\Entity;
use App\Entity\Partenaire;

use App\Repository\TerrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   /**
     *  @Groups({"post:read"})
    */
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"surface est obligatoire")]
    #[Assert\Positive(message:"le surface doit etre positif")]
   /**
     *  @Groups({"post:read"})
    */
    private ?float $surface = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"l adresse est obligatoire")]
    #[Assert\Length(min:5,minMessage:"Veuillez écrire au moins 5 caractéres")]
   /**
     *  @Groups({"post:read"})
    */
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"le potentiel est obligatoire")]
    #[Assert\Positive(message:"le potentiel doit etre positif")]
   /**
     *  @Groups({"post:read"})
    */
    private ?float $potentiel = null;

    #[ORM\ManyToOne(inversedBy: 'terrains')]
   /**
     *  @Groups({"post:read"})
    */
    private ?Partenaire $id_partenaire = null;

    #[ORM\OneToMany(mappedBy: 'id_terrain', targetEntity: Solde::class)]
    private Collection $soldes;

    #[ORM\OneToMany(mappedBy: 'terrain_id', targetEntity: Affectations::class)]
    private Collection $affectations;


    public function __construct()
    {
        $this->soldes = new ArrayCollection();
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


    /**
     * @return Collection<int, Solde>
     */
    public function getSoldes(): Collection
    {
        return $this->soldes;
    }

    public function addSolde(Solde $solde): self
    {
        if (!$this->soldes->contains($solde)) {
            $this->soldes->add($solde);
            $solde->setIdTerrain($this);
        }

        return $this;
    }

    public function removeSolde(Solde $solde): self
    {
        if ($this->soldes->removeElement($solde)) {
            // set the owning side to null (unless already changed)
            if ($solde->getIdTerrain() === $this) {
                $solde->setIdTerrain(null);
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
            $affectation->setTerrainId($this);
        }

        return $this;
    }

    public function removeAffectation(Affectations $affectation): self
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getTerrainId() === $this) {
                $affectation->setTerrainId(null);
            }
        }

        return $this;
    }

    /*public function getMembre(): ?Membre
    {
        return $this->Membre;
    }

    public function setMembre(?Membre $Membre): self
    {
        $this->Membre = $Membre;

        return $this;
    }*/
    public function __toString()
    {
        return $this->id;
    }

   
    public function getIdPartenaire(): ?Partenaire
    {
        return $this->id_partenaire;
    }

    public function setIdPartenaire(?Partenaire $partenaire): self
    {
        $this->id_partenaire = $partenaire;

        return $this;
    }
    
}
