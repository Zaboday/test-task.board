<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Services\Registration\RegistrationServiceInterface;

/**
 * Контроллер для регистрации пользователя.
 */
class UserRegisterController extends BaseApiController
{
    /**
     * Log-in существующего юзера.
     *
     * @param LoginUser                    $request
     * @param RegistrationServiceInterface $service
     *
     * @return mixed
     */
    public function login(LoginUser $request, RegistrationServiceInterface $service)
    {
        $user = $service->getUserByCredentials($request->get('email'), $request->get('password'));
        if (!$user) {
            return response()->json($this->formatResponseErrData('Вход в систему с указанными данными невозможен.'), 401);
        }

        return $this->formatResponseData(['user' => $user, 'api_token' => $user->api_token]);
    }

    /**
     * @param RegisterUser                 $request
     * @param RegistrationServiceInterface $service
     *
     * @return array
     */
    public function register(RegisterUser $request, RegistrationServiceInterface $service): array
    {
        $service->register(
            $request->get('email'),
            $request->get('password'),
            $request->get('name')
        );

        return $this->formatResponseData('ok');
    }
}
