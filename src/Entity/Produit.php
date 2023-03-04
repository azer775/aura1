<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Groups("produit")]
    private ?string $nom_Prod = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"champ obligatoire")]
    //#[Assert\Length(min:20,minMessage:"Veuillez écrire au moins 20 caractéres")]
    #[Groups("produit")]
    private ?string $description = null;

    #[ORM\Column(length: 1000)]
    //#[Assert\Url()]
    #[Assert\NotBlank(message:"Image est obligatoire")]
    #[Groups("produit")]
    private ?string $image = null;

    #[ORM\Column]
    #[Assert\Positive(message:"prix doit etre Positive")]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Groups("produit")]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"champ obligatoire")]
    #[Assert\Positive(message:"nombre de produit doit etre Positive")]
    #[Groups("produit")]
    private ?int $nbr_Prods = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups("produit")]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Achat::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $achats;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProd(): ?string
    {
        return $this->nom_Prod;
    }

    public function setNomProd(string $nom_Prod): self
    {
        $this->nom_Prod = $nom_Prod;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbrProds(): ?int
    {
        return $this->nbr_Prods;
    }

    public function setNbrProds(int $nbr_Prods): self
    {
        $this->nbr_Prods = $nbr_Prods;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setProduit($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getProduit() === $this) {
                $achat->setProduit(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom_Prod.' '.$this->id;
    }
}
