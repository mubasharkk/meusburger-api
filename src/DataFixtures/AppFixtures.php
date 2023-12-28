<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $data = \json_decode(file_get_contents(__DIR__.'/data/tasks.json'));

        foreach ($data as $item) {
            $task = new Task();
            $task->title = $item->title;
            $task->description = $item->description;
            $task->status = $item->status;
            $task->tags = $item->tags;
            $task->due_date = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $item->due_date
            );
            $manager->persist($task);
        }
        $manager->flush();
    }
}
