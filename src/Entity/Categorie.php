<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Théme est obligatoire")]
    #[Groups("produit")]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $produits;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Url()]
    #[Assert\NotBlank(message:"Image est obligatoire")]
    private ?string $image = null;

    
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Positive(message:"nombre de produit doit etre Positive")]
    #[ORM\Column(nullable: true)]
    private ?int $nbrproduit = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom.' '.$this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNbrproduit(): ?int
    {
        return $this->nbrproduit;
    }

    public function setNbrproduit(?int $nbrproduit): self
    {
        $this->nbrproduit = $nbrproduit;

        return $this;
    }
}
