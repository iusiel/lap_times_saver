<?php

namespace App\Entity;

use App\Repository\LapTimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @SuppressWarnings(PHPMD.CamelCaseParameterName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 *
 */
#[ORM\Entity(repositoryClass: LapTimeRepository::class)]
class LapTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $Date;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $Game;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $Car;

    #[ORM\ManyToOne(targetEntity: Track::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $Track;

    #[ORM\Column(type: 'string', length: 255)]
    private $Time;

    #[ORM\Column(type: 'boolean')]
    private $IsPractice;

    #[ORM\Column(type: 'string', length: 255)]
    private $ExtraNotes;

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

    public function getTime(): ?string
    {
        return $this->Time;
    }

    public function setTime(string $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

    public function getIsPractice(): ?bool
    {
        return $this->IsPractice;
    }

    public function setIsPractice(bool $IsPractice): self
    {
        $this->IsPractice = $IsPractice;

        return $this;
    }

    public function getExtraNotes(): ?string
    {
        return $this->ExtraNotes;
    }

    public function setExtraNotes(string $ExtraNotes): self
    {
        $this->ExtraNotes = $ExtraNotes;

        return $this;
    }
}
