<?php

namespace App\Entity;

use App\Repository\TechnicienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TechnicienRepository::class)]
class Technicien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Nom Invalide")]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Prenom Invalide")]
    private ?string $prenom = null;

    #[ORM\Column(length: 8)]
    #[Assert\Positive(message:"Telephone Invalide")]
    #[Assert\NotBlank(message:"Telephone Invalide")]
    private ?string $tel = null;

    #[ORM\Column(length: 50)]
    #[Assert\Email(message:"Email Invalide")]
    #[Assert\NotBlank(message:"Email Invalide")]
    private ?string $email = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(message:"Spécialité Invalide")]
    #[Assert\Positive(message:"Spécialité Invalide")]
    private ?string $specialite = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Salaire Invalide")]
    #[Assert\Positive(message:"Salaire Invalide")]
    private ?float $salaire = null;

   

    #[ORM\OneToMany(mappedBy: 'technicien', targetEntity: Affectations::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $affectations;

    public function __construct()
    {
      
        $this->affectations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * @return Collection<int, Terrain>
     */
    public function getAffectation(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Terrain $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
        }

        return $this;
    }

    public function removeAffectation(Terrain $affectation): self
    {
        $this->affectations->removeElement($affectation);

        return $this;
    }
   
    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }
    
    public function __toString()
    {
        return $this->nom;
    }
}
