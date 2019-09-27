<?php

namespace App\Services\Storage\Contracts;

/**
 * Storage interface with CRUD.
 */
interface StorageInterface
{
    /**
     * Create entity.
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function create(array $attributes): bool;

    /**
     * Find by $where clause.
     *
     * @param array $where
     *
     * @return mixed
     */
    public function findBy(array $where);

    /**
     * Find by $id.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Count entity by $where.
     *
     * @param array $where
     *
     * @return int
     */
    public function count(array $where = []): int;

    /**
     * Get page of entities.
     *
     * @param int   $page
     * @param int   $limit
     * @param array $where
     *
     * @return mixed
     */
    public function page(int $page, int $limit, array $where = []);

    /**
     * Delete entity.
     *
     * @param mixed $id
     */
    public function delete($id): void;
}
