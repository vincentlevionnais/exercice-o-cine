<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * Clé primaire
     * Auto-increment
     * type INT
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Titre
     * 
     * @ORM\Column(type="string", length=211)
     */
    private $title;

    /**
     * Date de création
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Date de MaJ
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, mappedBy="movie")
     */
    private $genres;

    /**
     * @ORM\ManyToOne(targetEntity=Casting::class, inversedBy="movies")
     */
    private $casting;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }


    /**
     * Get clé primaire
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get titre
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titre
     *
     * @return  self
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get date de création
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set date de création
     *
     * @return  self
     */ 
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get date de MaJ
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set date de MaJ
     *
     * @return  self
     */ 
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Remove movie
     *
     */
    public function removeMovie($movie)
    {
        $this->movie->remove($movie);
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeMovie($this);
        }

        return $this;
    }

    public function getCasting(): ?Casting
    {
        return $this->casting;
    }

    public function setCasting(?Casting $casting): self
    {
        $this->casting = $casting;

        return $this;
    }
}