<?php

namespace App\Tests;

use App\Entity\Task;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost',
        ]);
    }

    public function testGetTasksList()
    {
        $response = $this->client->get('/api/tasks');

        $json = json_decode($response->getBody(), true);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->getBody());
    }

    public function testCreateTask()
    {
        $task = [
            'title'       => 'Egestas vestibulum montes ex?',
            'description' => 'Egestas vestibulum montes ex? Elementum accusamus, elementum metus consequatur nemo pretium in atque purus viverra?',
            'status'      => 'done',
            'due_date'    => "2023-12-30 19:15:45",
            'tags'        => ['IT', 'Dev'],
        ];

        $response = $this->client->post('/api/tasks', ['form_params' => $task]);

        $this->assertEquals($response->getStatusCode(), 201);

        $json = \json_decode($response->getBody(), true);

        $this->assertEquals($json['title'], $task['title']);
        $this->assertEquals($json['status'], $task['status']);
    }

    public function testPutTask()
    {
        $task = [
            'title'  => 'Test Title',
            'status' => 'in-progress',
        ];

        $repo = $this->bootKernel()->getContainer()->get('doctrine')->getRepository(Task::class);

        $dt = $repo->findOneBy([]);

        $response = $this->client->put("/api/tasks/{$dt->getId()}", ['form_params' => $task]);

        $this->assertEquals($response->getStatusCode(), 201);

        $json = \json_decode($response->getBody(), true);

        $this->assertEquals($json['title'], $task['title']);
        $this->assertEquals($json['status'], $task['status']);
    }

    public function testDeleteTask()
    {
        $repo = $this->bootKernel()->getContainer()->get('doctrine')->getRepository(Task::class);

        $dt = $repo->findOneBy([]);

        $response = $this->client->delete("/api/tasks/{$dt->getId()}");

        $this->assertEquals($response->getStatusCode(), 200);

        $deleted = $repo->findOneBy(['id' => $dt->getId()]);
        $this->assertNull($deleted);
    }
}
