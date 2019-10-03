<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Storage;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use App\Services\Storage\EloquentStorage;
use App\Services\Storage\MessageEloquentStorage;

/**
 * @group storage
 * @group storage_eloquent
 */
class MessageEloquentStorageTest extends AbstractEloquentStorageTestCase
{
    /**
     * @return array
     */
    public function modelRelations(): array
    {
        return ['user'];
    }

    /**
     * @return Model
     */
    public function instanceModel(): Model
    {
        return new Message();
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

        return new MessageEloquentStorage($model);
    }
}
