<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(

)]
class Artist
{

    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    private string $name = "";

    #[Column(type: 'datetime')]
    #[Assert\NotNull]
    private ?\DateTimeInterface $formedDate = null;

    #[ORM\OneToMany(
        mappedBy: 'artist',
        targetEntity: Album::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private collection $albums;

    public function __construct()
    {
        $this->albums = new arrayCollection();
    }

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

    public function getAlbum(): iterable|ArrayCollection
    {
        return $this->albums;
    }
}