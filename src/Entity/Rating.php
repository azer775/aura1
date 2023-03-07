<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?post $post ;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
   // #[ORM\JoinColumn(nullable: false)]
    private ?Membre $membre ;

    #[ORM\Column]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $rate ;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?post
    {
        return $this->post;
    }

    public function setPost(?post $post): self
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

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setDateCreation(\DateTimeInterface $date_Creation): self
    {
        $this->createdAt = $date_Creation;

        return $this;
    }


}
