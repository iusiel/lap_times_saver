<?php

namespace App\Entity;

use App\Repository\LapTimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LapTimeRepository::class)]
class LapTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: Game::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $Game;

    #[ORM\OneToOne(targetEntity: Car::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $Car;

    #[ORM\OneToOne(targetEntity: Track::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $Track;

    #[ORM\Column(type: 'datetime')]
    private $Date;

    #[ORM\Column(type: 'datetime')]
    private $CreatedAt;

    #[ORM\Column(type: 'datetime')]
    private $UpdatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(Game $Game): self
    {
        $this->Game = $Game;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->Car;
    }

    public function setCar(Car $Car): self
    {
        $this->Car = $Car;

        return $this;
    }

    public function getTrack(): ?Track
    {
        return $this->Track;
    }

    public function setTrack(Track $Track): self
    {
        $this->Track = $Track;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }
}
