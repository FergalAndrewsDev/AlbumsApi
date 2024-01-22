<?php

declare(strict_types=1);

namespace App\Tests\functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Artist;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ArtistTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCollection(): void
    {
        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $response = $client->request('GET', '127.0.0.1:8000/api/artists');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame(
            'content-type',
            'application/ld+json; charset=utf-8'
        );
        $this->assertJsonContains([
            '@context' => '/api/contexts/Artist',
            '@id' => '/api/artists',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 10,
            'hydra:view' => [
                '@id' => '/api/artists?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/artists?page=1',
                'hydra:last' => '/api/artists?page=2',
                'hydra:next' => '/api/artists?page=2',
            ],
        ]);
        $this->assertCount(5, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Artist::class);
    }


    public function testCreateArtist(): void
    {
        static::createClient()->request('POST', '127.0.0.1:8000/api/artists', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'test_artist',
                'formedDate' => '1985-07-31T00:00:00+00:00',
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'name' => 'test_artist',
            'formedDate' => '1985-07-31T00:00:00+00:00',
        ]);
    }

    public function testCreateInvalidArtist(): void
    {
        static::createClient()->request('POST', '127.0.0.1:8000/api/artists', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => '',
                'formedDate' => '1985-07-31T00:00:00+00:00',
            ],
        ]);
        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateArtist(): void
    {
        static::createClient()->request('PATCH', '127.0.0.1:8000/api/artists/1', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'name' => 'updated artist'
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/artists/1',
            'name' => 'updated artist'
        ]);
    }

    public function testPagination(): void
    {
        $client = static::createClient(['HTTP_HOST' => '127.0.0.1:8000']);
        $client->request('GET', '127.0.0.1:8000/api/artists?page=2');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Artist',
            '@id' => '/api/artists',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 10,
            'hydra:view' => [
                '@id' => '/api/artists?page=2',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/artists?page=1',
                'hydra:last' => '/api/artists?page=2',
                'hydra:previous' => '/api/artists?page=1',
            ],
        ]);
    }
}
