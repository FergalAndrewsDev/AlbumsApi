<?php

declare(strict_types=1);

namespace App\Tests\entity;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Artist;
use DateTimeImmutable;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ReflectionProperty;

class ArtistTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public Artist $artist;

    public function setUp(): void
    {
        $this->artist = new Artist();
    }

    public function testArtist(): void
    {
        $this->assertInstanceOf(Artist::class, $this->artist);
    }

    public function testGetId(): void
    {
        $property = new ReflectionProperty(Artist::class, 'id');
        $property->setValue($this->artist, 3);

        $this->assertSame(3, $this->artist->getId());
    }

    public function testGetName(): void
    {
        $testAlbumName = 'Test Artist';
        $this->artist->setName($testAlbumName);

        $this->assertSame($testAlbumName, $this->artist->getName());
    }

    public function testFormedDate(): void
    {
        $formedDate = new DateTimeImmutable();
        $formedDate = $formedDate->setDate(2001, 2, 3);;
        $this->artist->setFormedDate($formedDate);

        $this->assertSame($formedDate, $this->artist->getFormedDate());
    }
}
