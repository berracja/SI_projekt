<?php
/**
 * ToDoList service.
 */

namespace App\Service;

use App\Entity\ToDoList;
use App\Repository\ToDoListRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ToDoListService.
 */
class ToDoListService implements ToDoListServiceInterface
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    private const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param ToDoListRepository $todolistRepository ToDoList repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(private readonly ToDoListRepository $todolistRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->todolistRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @param ToDoList $todolist todolist
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ToDoList $todolist): void
    {
        if (null === $todolist->getId()) {
            $todolist->setCreatedAt(new \DateTimeImmutable());
        }

        $this->todolistRepository->save($todolist);
    }

    /**
     * Delete entity.
     *
     * @param ToDoList $todolist ToDoList entity
     */
    public function delete(ToDoList $todolist): void
    {
        $this->todolistRepository->delete($todolist);
    }
}
