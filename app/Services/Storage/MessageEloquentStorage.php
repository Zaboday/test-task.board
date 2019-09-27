<?php

namespace App\Services\Storage;

use App\Models\Message;

/**
 * Messages Storage.
 */
class MessageEloquentStorage extends EloquentStorage
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }
}
