<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\Feature\AbstractFeatureTestCase;

/**
 * @group  controller
 * @group  controller_message
 *
 * @covers \App\Http\Controllers\Api\MessagesController
 */
class MessagesControllerTest extends AbstractFeatureTestCase
{
    /**
     * Тест /api/messages запросом без токена.
     */
    public function testResponseWithoutTokenGet()
    {
        $response = $this->get('/api/messages');

        $response->assertStatus(200);
    }

    /**
     * Тест POST /api/messages запросом без токена.
     */
    public function testResponseWithoutTokenPost()
    {
        $response = $this->post('/api/messages');

        $response->assertStatus(401);
    }

    /**
     * Тест POST /api/messages запросом без токена.
     */
    public function testResponseWithoutTokenDelete()
    {
        $response = $this->delete('/api/messages/1');

        $response->assertStatus(401);
    }

    /**
     * Тест success ответа /api/messages со списком сообщений.
     */
    public function testSuccessResponseGet()
    {
        \factory(User::class)->create(['api_token' => $token = Str::random()]);
        $response = $this->get('/api/messages', ['Authorization' => 'Bearer '.$token]);
        $response->assertStatus(200)->assertJsonStructure(['data']);
        $data = $response->json()['data'];

        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertArrayHasKey('text', $data[0]);
        $this->assertArrayHasKey('created_at', $data[0]);
        $this->assertArrayHasKey('user', $data[0]);
    }

    /**
     * Тест ответа сервера на создание Message.
     */
    public function testSuccessResponsePost()
    {
        \factory(User::class)->create(['api_token' => $token = Str::random()]);
        $response = $this->post(
            '/api/messages', ['text' => 'some text', 'title' => Str::random()],
            ['Authorization' => 'Bearer '.$token, 'X-Requested-With' => 'XMLHttpRequest']
        );
        $response->assertStatus(201);
    }

    /**
     * Тест ответа сервера при удалении Message.
     */
    public function testSuccessResponseDelete()
    {
        $user = \factory(User::class)->create(['api_token' => $token = Str::random()]);
        $message = \factory(Message::class)->create(['user_id' => $user->id]);

        $response1 = $this->delete(
            '/api/messages/'.$message->id,
            [],
            ['Authorization' => 'Bearer '.$token, 'X-Requested-With' => 'XMLHttpRequest']
        );
        $response1->assertStatus(200)->assertJson(['data' => 'ok']);
    }
}
