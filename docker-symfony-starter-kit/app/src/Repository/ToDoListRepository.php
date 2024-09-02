<?php
/**
 * ToDoList repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ToDoListRepository.
 *
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<ToDoList>
 */
class ToDoListRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial todolist.{id, createdAt, title, deadline}',
                'partial category.{id, title}'
            )
            ->join('todolist.category', 'category')
            ->orderBy('todolist.createdAt', 'ASC');
    }

    /**
     * Count todolists by category.
     *
     * @param Category $category Category
     *
     * @return int Number of todolists in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('todolist.id'))
            ->where('todolist.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * Save entity.
     *
     * @param ToDoList $todolist ToDoList entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(ToDoList $todolist): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->persist($todolist);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param ToDoList $todolist ToDoList entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(ToDoList $todolist): void
    {
        assert($this->_em instanceof EntityManager);
        $this->_em->remove($todolist);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('todolist');
    }

}
