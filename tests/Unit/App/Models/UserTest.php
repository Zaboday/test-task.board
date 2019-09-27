<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @group model
 * @group model_user
 */
class UserTest extends AbstractModelTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getFillable(): array
    {
        return [
            'name',
            'email',
            'password',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getHidden(): array
    {
        return [
            'password',
            'api_token',
            'created_at',
            'updated_at',
            'id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function instanceFactory(): Model
    {
        return new User();
    }
}
