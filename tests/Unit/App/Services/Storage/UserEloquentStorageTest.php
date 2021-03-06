<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Storage;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Services\Storage\EloquentStorage;
use App\Services\Storage\UserEloquentStorage;

/**
 * @group  storage
 * @group  storage_eloquent
 * @covers \App\Services\Storage\UserEloquentStorage
 */
class UserEloquentStorageTest extends AbstractEloquentStorageTestCase
{
    /**
     * @return array
     */
    public function modelRelations(): array
    {
        return [];
    }

    /**
     * @return Model
     */
    public function instanceModel(): Model
    {
        return new User();
    }

    /**
     * @param null $model
     *
     * @return EloquentStorage
     */
    public function instanceFactory($model = null): EloquentStorage
    {
        if (!$model) {
            $model = $this->instanceModel();
        }

        return new UserEloquentStorage($model);
    }
}
