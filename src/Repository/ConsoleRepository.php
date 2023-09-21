<?php

namespace App\Repository;

use App\Entity\Console;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Console>
 *
 * @method Console|null find($id, $lockMode = null, $lockVersion = null)
 * @method Console|null findOneBy(array $criteria, array $orderBy = null)
 * @method Console[]    findAll()
 * @method Console[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Console::class);
    }

    
}
