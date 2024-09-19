<?php
/**
 * ToDoList service interface.
 */

namespace App\Service;

use App\Entity\ToDoList;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface ToDoListServiceInterface.
 */
interface ToDoListServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param ToDoList $todolist ToDoList entity
     */
    public function save(ToDoList $todolist): void;

    /**
     * Delete entity.
     *
     * @param ToDoList $todolist ToDoList entity
     */
    public function delete(ToDoList $todolist): void;
}
