<?php

declare(strict_types=1);

namespace App\Services\Storage;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Storage\Contracts\StorageInterface;

/**
 * Abstract Storage based on Eloquent.
 */
class EloquentStorage implements StorageInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Model relations.
     *
     * @var array
     */
    protected $relations = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create Entity.
     *
     * @param array $attributes
     *
     * @return bool
     */
    public function create(array $attributes): bool
    {
        $this->newModelQuery()->create($attributes);

        return true;
    }

    /**
     * Find by one $where clause.
     *
     * @param array $where
     *
     * @return Model|null
     */
    public function findBy(array $where): ?Model
    {
        return $this->newModelQuery()->where($where[0], $where[1], $where[2])->first();
    }

    /**
     * Find by identifier.
     *
     * @param mixed $id
     *
     * @return Model|null
     */
    public function find($id): ?Model
    {
        /** @var Model|null $result */
        $result = $this->newModelQuery()->find($id);

        return $result;
    }

    /**
     * Count by $where clause.
     *
     * @param array $where
     *
     * @return int
     */
    public function count(array $where = []): int
    {
        return $this->newModelQuery()->where($where[0], $where[1], $where[2])->count();
    }

    /**
     * Get page of entities.
     *
     * @param int   $page
     * @param int   $limit
     * @param array $where
     *
     * @return Collection
     */
    public function page(int $page, int $limit, array $where = []): Collection
    {
        $query = $this->newModelQuery();
        if ($where) {
            $query->where($where[0], $where[1], $where[2]);
        }

        return $query->forPage($page, $limit)->get();
    }

    /**
     * Delete entity.
     *
     * @param mixed $id
     *
     * @throws \Exception
     */
    public function delete($id): void
    {
        $query = $this->newModelQuery()->find($id);
        if ($query) {
            $query->delete();
        }
    }

    /**
     * Get current model.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get a new query builder for the model instance.
     *
     * @return Builder
     */
    protected function newModelQuery(): Builder
    {
        return $this->model->newQuery();
    }
}
