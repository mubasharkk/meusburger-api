<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NotebookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class NotebookController extends AbstractController
{
    #[Route('api/notes/slug/{id}')]
    public function __invoke(string $id, NotebookRepository $repo)
    {
        $note = $repo->findBy(['slug' => trim($id)]);

        if (!$note) {
            throw $this->createNotFoundException(
                'No note found for this slug'
            );
        }

        return $this->json($note[0]);
    }
}
