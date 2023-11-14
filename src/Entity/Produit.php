<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Categorie;



#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $quantiteEnStock = null;

    #[ORM\Column]
    private ?int $nombreDeVues = 0;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'products')]
    private ?Categorie $categorie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateMiseAJour = null;

    #[ORM\ManyToOne(targetEntity: Badge::class)]
    private ?Badge $badge;


    #[ORM\Column(type:"string", nullable: true)]
    private $imageName = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantiteEnStock(): ?int
    {
        return $this->quantiteEnStock;
    }

    public function setQuantiteEnStock(int $quantiteEnStock): static
    {
        $this->quantiteEnStock = $quantiteEnStock;

        return $this;
    }

    public function getNombreDeVues(): ?int
    {
        return $this->nombreDeVues;
    }

    public function setNombreDeVues(int $nombreDeVues): static
    {
        $this->nombreDeVues = $nombreDeVues;

        return $this;
    }

    public function incrementNombresDeVues(): static
    {
        $this->nombreDeVues = $this->nombreDeVues + 1;
        
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

    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    public function setBadge(?Badge $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateMiseAJour(): ?\DateTimeInterface
    {
        return $this->dateMiseAJour;
    }

    public function setDateMiseAJour(\DateTimeInterface $dateMiseAJour): static
    {
        $this->dateMiseAJour = $dateMiseAJour;

        return $this;
    }

    public function getImageName() : ?string
    {
       return $this->imageName;
    }

    public function setImageName(?string $newImageName): static
    {
        $this->imageName = $newImageName;
        return $this;
    }
}
