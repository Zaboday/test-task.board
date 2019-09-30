<?php

declare(strict_types=1);

namespace App\Services\Storage;

use App\Models\Message;
use App\Services\Storage\Contracts\MessageStorageInterface;

/**
 * Messages Storage.
 */
class MessageEloquentStorage extends EloquentStorage implements MessageStorageInterface
{
    public function __construct(Message $model)
    {
        parent::__construct($model);

        $this->relations = ['user'];
    }
}
