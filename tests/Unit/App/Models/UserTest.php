<?php

declare(strict_types=1);

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @group  model
 * @group  model_user
 *
 * @covers \App\Models\User
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
            'email',
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
