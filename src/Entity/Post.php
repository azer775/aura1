<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("posts","cmnts")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Théme est obligatoire")]
    #[Groups("posts")]
    private ?string $theme = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url()]
    #[Assert\NotBlank(message:"Image est obligatoire")]
    #[Groups("posts")]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Contenu est obligatoire")]
    #[Assert\Length(min:20,minMessage:"Veuillez écrire au moins 20 caractéres")]
    #[Groups("posts")]
    private ?string $contenu = null;

    #[ORM\Column]
    #[Groups("posts")]
    private ?int $nbr_Vue = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
  //  #[Assert\Date()]
    #[Groups("posts")]
    private ?\DateTimeInterface $date_Creation = null;

  //  #[ORM\ManyToOne(inversedBy: 'posts')]
   // private ?Admin $admin = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Commentaire::class,cascade:["remove"], orphanRemoval:true)]
    #[Groups("posts")]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Membre $Membre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Nom du post est obligatoire")]
    #[Groups("posts")]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Rating::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $ratings;

    

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getNbrVue(): ?int
    {
        return $this->nbr_Vue;
    }

    public function setNbrVue(int $nbr_Vue): self
    {
        $this->nbr_Vue = $nbr_Vue;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_Creation;
    }

    public function setDateCreation(\DateTimeInterface $date_Creation): self
    {
        $this->date_Creation = $date_Creation;

        return $this;
    }

  /*  public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
*/
    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom.' '.$this->id;
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
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setPost($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPost() === $this) {
                $rating->setPost(null);
            }
        }

        return $this;
    }

  
}
