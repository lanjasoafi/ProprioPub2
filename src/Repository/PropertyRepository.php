<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

   

    public function findBySearchCriteria($keyword, $type, $adress, $surface, $chambre, $status, $price)
{
    $queryBuilder = $this->createQueryBuilder('p');

    if ($keyword) {
        $queryBuilder->andWhere('p.title LIKE :keyword')
            ->orWhere('p.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%');
    }

    if ($type && $type !== 'all') {
        $queryBuilder->andWhere('p.type = :type')
            ->setParameter('type', $type);
    }

    if ($adress) {
        $queryBuilder->andWhere('p.adress = :adress')
            ->setParameter('adress', $adress);
    }

    if ($surface) {
        $queryBuilder->andWhere('p.surface >= :surface')
            ->setParameter('surface', $surface);
    }

    if ($chambre) {
        $queryBuilder->andWhere('p.chambre >= :chambre')
            ->setParameter('chambre', $chambre);
    }

    if ($status && $status !== 'all') {
        $queryBuilder->andWhere('p.status = :status')
            ->setParameter('status', $status);
    }

    if ($price) {
        $price = floatval($price);  // Convertit la chaîne en décimal
        $queryBuilder->andWhere('p.price <= :price')
            ->setParameter('price', $price);
    }
    

    return $queryBuilder->getQuery()->getResult();
}



//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
