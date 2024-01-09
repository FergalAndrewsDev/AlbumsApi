<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity]
#[ApiResource(

)]
class Artist
{
    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    private string $name = "";

    #[Column(type: 'datetime')]
    private ?\DateTimeInterface $formedDate = null;

    #[OneToMany(
        targetEntity: "Album",
        mappedBy: "Artist",
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private iterable $album;

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

    public function getFormedDate(): ?\DateTimeInterface
    {
        return $this->formedDate;
    }

    public function setFormedDate(?\DateTimeInterface $formedDate): void
    {
        $this->formedDate = $formedDate;
    }


}