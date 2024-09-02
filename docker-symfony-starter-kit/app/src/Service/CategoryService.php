<?php
/**
 * Category service.
 */

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\NoteRepository;
use App\Repository\ToDoListRepository;
use App\Entity\Note;

// ...
/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
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
     * @param CategoryRepository $CategoryRepository Category repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function __construct(private readonly CategoryRepository $CategoryRepository, private readonly PaginatorInterface $paginator, private readonly NoteRepository $NoteRepository, private readonly ToDoListRepository $ToDoListRepository)
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
            $this->CategoryRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        if (null == $category->getId()) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->CategoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        if (null == $category->getId()) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }

        $this->CategoryRepository->delete($category);
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try {
            $result = ($this->NoteRepository->countByCategory($category)||($this->ToDoListRepository->countByCategory($category)));

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }
}