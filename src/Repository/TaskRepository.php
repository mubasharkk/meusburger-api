<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getByFilter(
        ?string $search = null,
        ?string $status = null,
        int     $limit = 50,
        int     $page = 0,
    ) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->select(
            't'
        )->from(Task::class, 't');

        if ($status) {
            $queryBuilder = $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    't.status',
                    $queryBuilder->expr()->literal($status)
                )
            );
        }

        if ($search) {
            $queryBuilder = $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like(
                        't.title',
                        $queryBuilder->expr()->literal("%{$search}%")
                    ),
                    $queryBuilder->expr()->like(
                        't.description',
                        $queryBuilder->expr()->literal("%{$search}%")
                    )
                )
            );
        }

        // json search doctrine not availble
        //foreach ($tags as $tag) {
        //    $queryBuilder->andWhere("(t.tags)::jsonb ? '{$tag}'");
        //}

        $page = $page < 1 ? 0 : $page;

        $queryBuilder = $queryBuilder
            ->orderBy('t.created_at', 'DESC')
            ->setFirstResult($page ? $page * $limit - 1: 0)
            ->setMaxResults($limit);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    public function createTask(
        string  $title,
        string  $status,
        ?string $description = null,
        ?string $dueDate = null,
        array   $tags = []
    ): Task {
        $task = new Task();
        $task->title = trim($title);
        $task->setDescription($description);
        $task->tags = $tags;
        $task->status = trim($status);
        $task->due_date = $this->convertDateTime(trim($dueDate));

        return $task;
    }

    private function convertDateTime(string $date): ?\DateTime
    {
        $format = str_contains($date, ':') ? 'Y-m-d H:i:s' : 'Y-m-d';

        return \DateTime::createFromFormat($format, $date) ?? null;
    }

    public function save(Task $task)
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function delete(Task $task)
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}
