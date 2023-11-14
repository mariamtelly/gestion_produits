<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePublication = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateMiseAJour = null; 

    #[ORM\Column]
    private ?int $nombreDeVues = 0;

    #[ORM\ManyToOne(targetEntity: ArticleCategorie::class)]
    private ?ArticleCategorie $articleCategorie;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeInterface $datePublication): static
    {
        $this->datePublication = $datePublication;

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

    public function getArticleCategorie(): ?ArticleCategorie
    {
        return $this->articleCategorie;
    }

    public function setArticleCategorie(ArticleCategorie $articleCategorie): static
    {
        $this->articleCategorie = $articleCategorie;
        
        return $this;
    }
}