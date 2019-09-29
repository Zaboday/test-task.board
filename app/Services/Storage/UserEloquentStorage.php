<?php

declare(strict_types=1);

namespace App\Services\Storage;

use App\Models\User;
use App\Services\Storage\Contracts\UserStorageInterface;

/**
 * Users Storage.
 */
class UserEloquentStorage extends EloquentStorage implements UserStorageInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
