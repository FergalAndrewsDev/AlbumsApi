<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity]
#[ApiResource]
class Album
{
    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    private string $name = "";

    #[Column(type: 'string', length: 40)]
    private string $genre = "";

    #[Column(type: 'text')]
    private string $description = "";

    #[Column(type: 'date')]
    private ?\DateTimeInterface $recordedDate = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getRecordedDate(): ?\DateTimeInterface
    {
        return $this->recordedDate;
    }

    public function setRecordedDate(?\DateTimeInterface $recordedDate): void
    {
        $this->recordedDate = $recordedDate;
    }



}