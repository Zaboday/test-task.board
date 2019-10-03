<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Models\User;
use Tests\Feature\AbstractFeatureTestCase;

/**
 * @group  controller
 * @group  controller_user_register
 *
 * @covers \App\Http\Controllers\Api\UserRegisterController
 */
class UserRegisterControllerTest extends AbstractFeatureTestCase
{
    /**
     * Тест /api/login с неправильным паролем.
     *
     * @return void
     */
    public function testLoginValidation()
    {
        $response = $this->post(
            '/api/login',
            ['email' => 'not.admin1@test.com', 'password' => 'invalid'],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(401);
    }

    /**
     * @return void
     */
    public function testLoginSuccess()
    {
        \factory(User::class)->create(['email' => $email = $this->faker->safeEmail]);
        $response = $this->post(
            '/api/login',
            ['email' => $email, 'password' => 'secret'],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200)->assertJsonStructure(['data' => ['user', 'api_token']]);
    }

    /**
     * Тест /api/register с невалидными данными.
     *
     * @return void
     */
    public function testRegisterRulesName()
    {
        $response = $this->post(
            '/api/register',
            [
                'email' => sprintf('not.admin%s@test.com', uniqid('_', true)),
                'password' => 'as',
            ],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors(
            [
                'name' => 'The name field is required.',
            ]
        );
        $response->assertSessionHasErrors(
            [
                'password' => 'The password must be at least 3 characters.',
            ]
        );

        $response = $this->post(
            '/api/register',
            [
                'email' => sprintf('not.admin%s@test.com', uniqid('_', true)),
                'name' => 'asd2Aasds',
            ],
            ['X-Requested-With' => 'XMLHttpRequest']
        );
        $response->assertStatus(302);
        $response->assertSessionHasErrors(
            [
                'password' => 'The password field is required.',
            ]
        );

        $response = $this->post(
            '/api/register',
            [
                'email' => sprintf('not.admin%s@test.com', uniqid('_', true)),
                'name' => 'asdasd-asd',
                'password' => 'asd2Aasds',
            ],
            ['X-Requested-With' => 'XMLHttpRequest']
        );
        $response->assertStatus(302);
        $response->assertSessionHasErrors(
            [
                'name' => 'The name may only contain letters and numbers.',
            ]
        );
    }

    /**
     * Тест /api/register с валидными данными.
     *
     * @return void
     */
    public function testRegisterSuccess()
    {
        $response = $this->post(
            '/api/register',
            [
                'email' => sprintf('not.admin%s@test.com', uniqid('_', true)),
                'password' => 'asd2Asdasdasd',
                'password_confirmation' => 'asd2Asdasdasd',
                'name' => 'asdAsd22',
            ],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200)->assertJson(['data' => 'ok']);
    }
}
