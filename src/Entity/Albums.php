<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity]
#[ApiResource]
class Albums
{
    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    private string $name = "";

    #[Column(type: 'text')]
    private ?string $description = "";

    #[Column(type: 'string', length: 40)]
    private string $genre = "";

    #[ManyToOne(
        targetEntity: "Artist",
        cascade: ["all"],
        fetch: "EAGER"
    )]
    private ?Artist $artist = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }


}