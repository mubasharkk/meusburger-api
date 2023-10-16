<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\Uid\Uuid;

class NotesApiTest extends ApiTestCase
{
    public function testGetAllNotes(): void
    {
        $response = static::createClient([],
            ['headers' => ['accept' => 'application/json']]
        )->request(
            'GET',
            '/api/notes'
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesJsonSchema([
            'id',
            'slug',
            'headline',
            'content',
            'created_at'
        ]);
    }

    public function testCreateNote(): void
    {
        $headline = 'Varius, justo voluptates';
        $content = 'audantium dolores bibendum animi, voluptatem maxime, excepteur laoreet quis aliquam.';
        $response = static::createClient([],
            ['headers' => ['accept' => 'application/json']]
        )->request(
            'POST',
            '/api/notes',
            ['json' => ['headline' => $headline, 'content' => $content]]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'headline' => $headline,
            'content' => $content,
            'slug' => 'varius-justo-voluptates'
        ]);

        $data = \json_decode($response->getContent(), true);
        $this->assertTrue(Uuid::isValid($data['id']));
    }
}
