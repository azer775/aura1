<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom est obligatoire")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"prenom est obligatoire")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email()]
    #[Assert\NotBlank(message:"email est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"PASSWORD est obligatoire")]
    /*#[SecurityAssert\UserPassword(
        message: 'Wrong value for your current password',
    )]*/
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNais = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"TEL est obligatoire")]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"adresse est obligatoire")]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'id_Membre', targetEntity: Don::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $dons;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: Commentaire::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: Facture::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: Achat::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $achats;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: FraisEnergie::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $fraisEnergies;

    #[ORM\OneToMany(mappedBy: 'Membre', targetEntity: Post::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $posts;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'Membre', targetEntity: Terrain::class,cascade:["remove"], orphanRemoval:true)]
    private Collection $terrains;

    public function __construct()
    {
        $this->dons = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->fraisEnergies = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->terrains = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->dateNais;
    }

    public function setDateNais(\DateTimeInterface $dateNais): self
    {
        $this->dateNais = $dateNais;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->dons->contains($don)) {
            $this->dons->add($don);
            $don->setMembre($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getMembre() === $this) {
                $don->setMembre(null);
            }
        }

        return $this;
    }

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
            $commentaire->setMembre($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getMembre() === $this) {
                $commentaire->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setMembre($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getMembre() === $this) {
                $facture->setMembre(null);
            }
        }

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
            $achat->setMembre($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getMembre() === $this) {
                $achat->setMembre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FraisEnergie>
     */
    public function getFraisEnergies(): Collection
    {
        return $this->fraisEnergies;
    }

    public function addFraisEnergy(FraisEnergie $fraisEnergy): self
    {
        if (!$this->fraisEnergies->contains($fraisEnergy)) {
            $this->fraisEnergies->add($fraisEnergy);
            $fraisEnergy->setMembre($this);
        }

        return $this;
    }

    public function removeFraisEnergy(FraisEnergie $fraisEnergy): self
    {
        if ($this->fraisEnergies->removeElement($fraisEnergy)) {
            // set the owning side to null (unless already changed)
            if ($fraisEnergy->getMembre() === $this) {
                $fraisEnergy->setMembre(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom.' '.$this->id;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setMembre($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getMembre() === $this) {
                $post->setMembre(null);
            }
        }

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Terrain>
     */
    public function getTerrains(): Collection
    {
        return $this->terrains;
    }

    public function addTerrain(Terrain $terrain): self
    {
        if (!$this->terrains->contains($terrain)) {
            $this->terrains->add($terrain);
            $terrain->setMembre($this);
        }

        return $this;
    }

    public function removeTerrain(Terrain $terrain): self
    {
        if ($this->terrains->removeElement($terrain)) {
            // set the owning side to null (unless already changed)
            if ($terrain->getMembre() === $this) {
                $terrain->setMembre(null);
            }
        }

        return $this;
    }
}
