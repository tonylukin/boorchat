<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function calculateAnswers(User $user): float
    {
        $query = $this->createQueryBuilder('u')
            ->select('SUM(a.vector * q.weight)')
            ->leftJoin('u.answers', 'a')
            ->innerJoin('a.question', 'q')
            ->where('u = :user')
            ->setParameter(':user', $user)
            ->groupBy('u.id')
            ->getQuery()
        ;
        return (float)$query->getSingleScalarResult();
    }

    public function getSimilarUserByAnswerIndex(User $user, float $answerIndex, array $exceptUsers): ?User
    {
        $query = $this->createQueryBuilder('u')
            ->leftJoin('u.answers', 'a')
            ->innerJoin('a.question', 'q')
            ->where('u != :user')
            ->setParameter(':user', $user)
            ->groupBy('u.id')
            ->orderBy("ABS(SUM(a.vector * q.weight) - {$answerIndex})", 'ASC')
            ->setMaxResults(1)
        ;
        if (!empty($exceptUsers)) {
            $query
                ->andWhere('u NOT IN (:exceptUsers)')
                ->setParameter(':exceptUsers', $exceptUsers) // TODO type array string
            ;
        }
        return $query->getQuery()->getOneOrNullResult();
    }
}
