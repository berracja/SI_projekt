<?php
/**
 * Note service.
 */

namespace App\Service;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class NoteService.
 */
class NoteService implements NoteServiceInterface
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
     * @param NoteRepository     $noteRepository noteRepository
     * @param PaginatorInterface $paginator      paginator
     */
    public function __construct(private readonly NoteRepository $noteRepository, private readonly PaginatorInterface $paginator)
    {
    }

    /**
     * @param int $page page
     *
     * @return PaginationInterface PaginationInterface
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->noteRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * @param Note $note note
     *
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Note $note): void
    {
        if (null === $note->getId()) {
            $note->setCreatedAt(new \DateTimeImmutable());
        }
        $note->setUpdatedAt(new \DateTimeImmutable());

        $this->noteRepository->save($note);
    }

    /**
     * Delete entity.
     *
     * @param Note $note Note entity
     */
    public function delete(Note $note): void
    {
        $this->noteRepository->delete($note);
    }
}
