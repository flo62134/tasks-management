<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Task find($id, $lockMode = null, $lockVersion = null)
 * @method null|Task findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function countBy(Project $project = null, \DateTime $from = null, \DateTime $to = null): int
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
        ;

        if ($project) {
            $queryBuilder->where('t.project = :project')
                ->setParameter('project', $project->getId())
            ;
        }

        if ($from) {
            $queryBuilder->where('t.startDate >= :from')
                ->setParameter('from', $from->format(\DateTime::ATOM))
            ;
        }

        if ($to) {
            $queryBuilder->where('t.endDate <= :to')
                ->setParameter('to', $to->format(\DateTime::ATOM))
            ;
        }

        return $queryBuilder
            ->getQuery()
            ->getResult()[0][1];
    }
}
