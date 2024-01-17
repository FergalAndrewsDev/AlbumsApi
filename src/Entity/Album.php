<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GET(
            uriTemplate: 'artist/{id}/album',
            uriVariables: [
                'id' => new Link(
                    fromProperty: 'artist',
                    fromClass: Album::class
                )
            ],
        ),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['album.read']],
    denormalizationContext: ['groups' => ['album.write']],
    paginationItemsPerPage: 5,
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'name' => 'partial',
        'artist.name' => 'partial',
        'artist.id' => 'exact',
    ]
)]
class Album
{
    #[GeneratedValue]
    #[Id, Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Groups(['album.read', 'album.write'])]
    private string $name = "";

    #[Column(type: 'text')]
    #[Groups(['album.read', 'album.write'])]
    private ?string $description = "";

    #[Assert\NotBlank]
    #[Column(type: 'string', length: 40)]
    #[Groups(['album.read', 'album.write'])]
    private string $genre = "";

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'albums')]
    #[Groups(['album.read'])]
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

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): void
    {
        $this->artist = $artist;
    }
}
