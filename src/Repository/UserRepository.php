<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function transform(User $data)
    {
        $arrayData = [
            "id" => $data->getId(),
            "name" => $data->getName(),
            "lastname" => $data->getLastname(),
            "email" => $data->getUserIdentifier(),
            "role" => $data->getRoles() ? $data->getRoles()[0] : null,
            "active" => $data->isActive() ? $data->isActive() : false,
            "createdAt" => $data->getCreatedAtFormatted() ? $data->getCreatedAtFormatted() : null,
            "updatedAt" => $data->getUpdatedAtFormatted() ? $data->getUpdatedAtFormatted() : null,
        ];

        $jsonString = json_encode(
            $arrayData
        );
        
        $objectData = json_decode($jsonString);
        
        return $objectData;
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
