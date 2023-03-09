<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Type('float')]
    #[Assert\Positive]
    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'Veuillez fournir un montant.')]
    private ?float $montant = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $date_Don = null;

    #[Assert\Regex(
        pattern: '/^[0-9]+$/',
        message: 'La carte de crédit ne doit contenir que des chiffres.'
    )]
    #[Assert\Length(
        min: 16,
        max: 16,
        exactMessage: 'La carte de crédit doit avoir une longueur de 16 alphabet.'
    )]
    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez fournir une Carte Credit.')]
    private ?string $carteCredit = null;

    #[ORM\Column(nullable: true)]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez fournir une adresse e-mail.')]
    #[Assert\Email(message: 'L\'adresse e-mail "{{ value }}" n\'est pas valide.')]
    #[Assert\Length(max: 255, maxMessage: 'L\'adresse e-mail ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $email;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Membre $membre = null;

    #[ORM\ManyToOne(inversedBy: 'dons')]
    private ?Association $association = null;

    public function __construct()
    {
        $this->date_Don = new DateTimeImmutable();
    }

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

    public function getDateDon(): ?DateTimeInterface
    {
        return $this->date_Don;
    }

    public function setDateDon(DateTimeInterface $date_Don): self
    {
        $this->date_Don = $date_Don;

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

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getCarteCredit(): ?string
    {
        return $this->carteCredit;
    }

    public function setCarteCredit(?string $carteCredit): self
    {
        $this->carteCredit = $carteCredit;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

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
}