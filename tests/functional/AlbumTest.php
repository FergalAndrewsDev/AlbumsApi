<?php

declare(strict_types=1);

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Album;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class AlbumTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCollection(): void
    {
        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $response = $client->request('GET', '127.0.0.1:8000/api/alba');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame(
            'content-type',
            'application/ld+json; charset=utf-8'
        );

        $this->assertJsonContains([
            '@context' => '/api/contexts/Album',
            '@id' => '/api/alba',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/api/alba?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/alba?page=1',
                'hydra:last' => '/api/alba?page=20',
                'hydra:next' => '/api/alba?page=2',
            ],
        ]);
        $this->assertCount(5, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Album::class);
    }

    public function testCreateAlbum(): void
    {

        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $client->request('GET', '127.0.0.1:8000/api/alba');

        static::createClient()->request('POST', '127.0.0.1:8000/api/alba', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'Revolver',
                'description' => 'A great album',
                'genre' => 'Rock/Pop',
                'artist' => '/api/artists/4',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'name' => 'Revolver',
            'description' => 'A great album',
            'genre' => 'Rock/Pop',
        ]);
    }

    public function testCreateInvalidAlbum(): void
    {

        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $client->request('GET', '127.0.0.1:8000/api/alba');

        static::createClient()->request('POST', '127.0.0.1:8000/api/alba', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'Revolver',
                'description' => 'A great album',
                'genre' => 'Rock/Pop',
                'artist' => null,
            ],
        ]);
        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateAlbum(): void
    {
        static::createClient()->request('PATCH', '127.0.0.1:8000/api/alba/1', [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => [
                'name' => 'updated album'
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/alba/1',
            'name' => 'updated album'
        ]);
    }

    public function testPagination(): void
    {
        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $client->request('GET', '127.0.0.1:8000/api/alba?page=2');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Album',
            '@id' => '/api/alba',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/api/alba?page=2',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/alba?page=1',
                'hydra:last' => '/api/alba?page=20',
                'hydra:previous' => '/api/alba?page=1',
                'hydra:next' => '/api/alba?page=3',
            ],
        ]);
    }
}
