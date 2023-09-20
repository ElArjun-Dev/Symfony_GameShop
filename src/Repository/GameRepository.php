<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    //Méthode qui récupere un jeu par son id avec les,pteset l'age minimum

    public function getGameWithInfo($id)
    {
        //appel de l'entity manager
        $entityManager = $this->getEntityManager();

        //*************** METHODE REQUETE SQL STANDARD ************
        //On crée la query 
        // $query = $entityManager->createQuery(
        //     'SELECT
        //     g.id,
        //     g.title,
        //     g.description,
        //     g.imagePath,
        //     g.price,
        //     g.releaseDate,
        //     n.userNote,
        //     n.mediaNote,
        //     a.label,
        //     a.imagePath as imgPegi,
        //     a.id as ageId
        //     FROM App\Entity\Game g
        //     JOIN g.note n
        //     JOIN g.age a
        //     WHERE g.id = :id')->setParameter('id', $id);
        // return $query->getOneOrNullResult();



        // Get the entity manager
        $entityManager = $this->getEntityManager();

        // Create a query builder for DQL
        $qb = $entityManager->createQueryBuilder();

        // Build the query
        $query = $qb->select([
            'g.id',
            'g.title',
            'g.description',
            'g.imagePath',
            'g.price',
            'g.releaseDate',
            'n.userNote',
            'n.mediaNote',
            'a.label',
            'a.imagePath as imgPegi',
            'a.id as ageId'
        ])
            ->from(Game::class, 'g')
            ->join('g.note', 'n')
            ->join('g.age', 'a')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        // Execute the query and return the result
        return $query->getOneOrNullResult();
    }

    //Méthode qui récupere la liste des console par jeu
    public function getConsolesByGame($id)
    {
        $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT c.label, c.id
        //     FROM App\Entity\Game g
        //     JOIN g.consoles c
        //     WHERE g.id = :id')->setParameter('id', $id);

        //     return $query->getResult();

        $qb = $entityManager->createQueryBuilder();

        $query = $qb->select([
            'c.label',
            'c.id'
        ])
            ->from(Game::class, 'g')
            ->join('g.consoles', 'c')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();
    }

    //Méthode qui recupere toute les consoles avec le nombre de jeux associés
    public function getCountGameByConsole()
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select([
            'c.label',
            'c.id',
            'COUNT(g.id) as total'
        ])
            ->from(Game::class, 'g')
            ->join('g.consoles', 'c')
            ->groupBy('c.id')
            ->getQuery();

        return $query->getResult();
    }

    public function getGamesByConsole($id)
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select([
            'g.id',
            'c.label',
            'g.title',
            'g.imagePath',
            'g.price',
        ])
            ->from(Game::class, 'g')
            ->join('g.consoles', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        // Execute the query and return the result
        return $query->getResult();
    }

    
    //methode pour supprimer un jeu
    public function delete(Game $entity, bool $flush = false)
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(Game $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);
        
        if($flush){
            $this->getEntityManager()->flush();
        }
    }
}