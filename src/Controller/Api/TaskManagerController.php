<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskManagerController extends AbstractController
{
    #[Route('/api/task-manager', name: 'task-manager')]
    public function index()
    {
        //create a new Response object
        $response = new Response();

        //set the return value
        $response->setContent('Hello World!');

        //make sure we send a 200 OK status
        $response->setStatusCode(Response::HTTP_OK);

        // set the response content type to plain text
        $response->headers->set('Content-Type', 'text/plain');

        // send the response with appropriate headers
        $response->send();
    }
}
