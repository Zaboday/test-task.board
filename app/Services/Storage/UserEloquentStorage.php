<?php

namespace App\Services\Storage;

use App\Models\User;

/**
 * Users Storage.
 */
class UserEloquentStorage extends EloquentStorage
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
