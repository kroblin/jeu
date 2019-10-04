<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $pioche = [];

    /**
     * @ORM\Column(type="array")
     */
    private $main_j1 = [];

    /**
     * @ORM\Column(type="array")
     */
    private $main_j2 = [];

    /**
     * @ORM\Column(type="array")
     */
    private $plateau = [];

    /**
     * @ORM\Column(type="array")
     */
    private $terrain_j1 = [];

    /**
     * @ORM\Column(type="array")
     */
    private $terrain_j2 = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $tour;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $typeVictoire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur", inversedBy="partiesJ1")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur", inversedBy="partiesJ2")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur2;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Joueur", inversedBy="partiesGagner")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gagnant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateDebut;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPioche(): ?array
    {
        return $this->pioche;
    }

    public function setPioche(array $pioche): self
    {
        $this->pioche = $pioche;

        return $this;
    }

    public function getMainJ1(): ?array
    {
        return $this->main_j1;
    }

    public function setMainJ1(array $main_j1): self
    {
        $this->main_j1 = $main_j1;

        return $this;
    }

    public function getMainJ2(): ?array
    {
        return $this->main_j2;
    }

    public function setMainJ2(array $main_j2): self
    {
        $this->main_j2 = $main_j2;

        return $this;
    }

    public function getPlateau(): ?array
    {
        return $this->plateau;
    }

    public function setPlateau(array $plateau): self
    {
        $this->plateau = $plateau;

        return $this;
    }

    public function getTerrainJ1(): ?array
    {
        return $this->terrain_j1;
    }

    public function setTerrainJ1(array $terrain_j1): self
    {
        $this->terrain_j1 = $terrain_j1;

        return $this;
    }

    public function getTerrainJ2(): ?array
    {
        return $this->terrain_j2;
    }

    public function setTerrainJ2(array $terrain_j2): self
    {
        $this->terrain_j2 = $terrain_j2;

        return $this;
    }

    public function getTour(): ?int
    {
        return $this->tour;
    }

    public function setTour(int $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getTypeVictoire(): ?string
    {
        return $this->typeVictoire;
    }

    public function setTypeVictoire(string $typeVictoire): self
    {
        $this->typeVictoire = $typeVictoire;

        return $this;
    }

    public function getJoueur1(): ?Joueur
    {
        return $this->joueur1;
    }

    public function setJoueur1(?Joueur $joueur1): self
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    public function getJoueur2(): ?Joueur
    {
        return $this->joueur2;
    }

    public function setJoueur2(?Joueur $joueur2): self
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    public function getGagnant(): ?Joueur
    {
        return $this->gagnant;
    }

    public function setGagnant(?Joueur $gagnant): self
    {
        $this->gagnant = $gagnant;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): self
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }


}
