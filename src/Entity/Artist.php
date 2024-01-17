<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GET(),
        new GetCollection(),
        new Post(),
        new Patch(),
    ],
    formats: ['jsonld'],
    normalizationContext: ['groups' => ['artist.read']],
    denormalizationContext: ['groups' => ['artist.write']],
    paginationItemsPerPage: 5,
)]
#[ApiFilter(
    SearchFilter::class, properties: ['name' => 'partial', 'formedDate' => 'DESC']
)]
class Artist
{

    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Groups(['album.read', 'artist.write', 'artist.read'])]
    private string $name = "";

    #[Column(type: 'datetime')]
    #[Assert\NotNull]
    #[Groups(['artist.read', 'artist.write', 'artist.read'])]
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