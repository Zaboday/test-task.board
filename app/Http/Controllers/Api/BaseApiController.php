<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Contains common for API controllers methods.
 */
class BaseApiController extends Controller
{
    /**
     * Общий формат данных для ответа.
     *
     * @param mixed $data
     *
     * @return array
     */
    protected function formatData($data): array
    {
        return ['data' => $data];
    }

    /**
     * Общий формат данных для ответа ошибки.
     *
     * @param mixed $message
     *
     * @return array
     */
    protected function formatErrData($message): array
    {
        return ['message' => $message];
    }
}
