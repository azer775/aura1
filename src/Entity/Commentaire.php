<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("cmnts","posts")]
    
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Commentaire ne peut pas etre vide")]
    #[Groups("cmnts")]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    #[Groups("cmnts")]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Groups("cmnts")]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires',cascade:['persist'])]
    #JoinColumn(nullable=false)
    private ?Membre $membre = null;

  

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }

   
    
}
