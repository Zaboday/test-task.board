<?php

declare(strict_types=1);

namespace Tests\Unit\App\Models;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * @group model
 * @group model_message
 */
class MessageTest extends AbstractModelTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getFillable(): array
    {
        return [
            'text',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getHidden(): array
    {
        return [
            'deleted_at',
            'updated_at',
            'user_id',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function instanceFactory(): Model
    {
        return new Message();
    }
}
