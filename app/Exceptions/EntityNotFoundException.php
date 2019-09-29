<?php

declare(strict_types=1);

namespace App\Exceptions;

/**
 * Выбрасывается если запись в таблице не была найдена.
 */
class EntityNotFoundException extends \LogicException
{
}
