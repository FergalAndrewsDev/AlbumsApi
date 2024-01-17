<?php

declare(strict_types=1);

namespace App\Tests\entity;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Album;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ReflectionProperty;

class AlbumTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public Album $album;

    public function setUp(): void
    {
        $this->album = new Album();
    }

    public function testAlbum(): void
    {
        $this->assertInstanceOf(Album::class, $this->album);
    }

    public function testGetId(): void
    {
        $property = new ReflectionProperty(Album::class, 'id');
        $property->setValue($this->album, 3);

        $this->assertSame(3, $this->album->getId());
    }

    public function testGetName(): void
    {
        $testAlbumName = 'Test Album';
        $this->album->setName($testAlbumName);

        $this->assertSame($testAlbumName, $this->album->getName());
    }
}
