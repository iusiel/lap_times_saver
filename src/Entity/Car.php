<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @SuppressWarnings(PHPMD.CamelCaseParameterName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 *
 */
#[UniqueEntity(fields: ['Name'])]
#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'datetime')]
    private $CreatedAt;

    #[ORM\Column(type: 'datetime')]
    private $UpdatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

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
