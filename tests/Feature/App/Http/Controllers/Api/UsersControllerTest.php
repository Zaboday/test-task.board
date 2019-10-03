<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\Feature\AbstractFeatureTestCase;

/**
 * @group  controller
 * @group  controller_user
 *
 * @covers \App\Http\Controllers\Api\UsersController
 */
class UsersControllerTest extends AbstractFeatureTestCase
{
    /**
     * Тест /api/user запросом без токена.
     */
    public function testResponseWithoutToken()
    {
        $response = $this->get('/api/users/me');

        $response->assertStatus(401);
    }

    /**
     * Тест /api/user запросом с невалидным токеном
     */
    public function testResponseWithInvalidToken()
    {
        $response = $this->get('/api/users/me', ['Authorization' => 'Bearer 0""00']);

        $response->assertStatus(401);
    }

    /**
     * Тест /api/user запросом с валидным токеном
     */
    public function testResponseWithValidToken()
    {
        \factory(User::class)->create(['api_token' => $token = Str::random()]);
        $response = $this->get('/api/users/me', ['Authorization' => 'Bearer '.$token]);

        $response->assertStatus(200)->assertJsonStructure(['data' => ['id', 'name', 'is_admin']]);
    }
}
