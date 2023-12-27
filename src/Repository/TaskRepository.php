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
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit =
 *         null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getByFilter(array $criteria, int $limit = 50)
    {
        return $this->findBy($criteria, ['created_at' => 'DESC'], $limit);
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

    //    /**
    //     * @return Task[] Returns an array of Task objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Task
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
