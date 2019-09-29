<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

/**
 * User endpoints.
 */
class UsersController extends BaseApiController
{
    /**
     * User info.
     *
     * @param Request $request
     *
     * @return array
     */
    public function show(Request $request): array
    {
        return $this->formatData($request->user());
    }
}
