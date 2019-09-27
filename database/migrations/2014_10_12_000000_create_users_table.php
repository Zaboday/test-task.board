<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Создание таблицы юзеров.
 */
class CreateUsersTable extends Migration
{
    /**
     * Пользователи для теста приложения.
     *
     * @var array
     */
    private $predefinedUsers = [
        [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'secret',
            'is_admin' => true,
            'api_token' => '111',
        ],
        [
            'name' => 'User',
            'email' => 'not.admin@admin.com',
            'password' => 'secret',
            'is_admin' => false,
            'api_token' => '222',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @throws Exception
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->timestamps();
        });

        $this->insertPredefinedUsers();
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }

    /**
     * Insert predefined Users.
     *
     * @throws Exception
     */
    private function insertPredefinedUsers(): void
    {
        foreach ($this->predefinedUsers as $user) {
            \DB::table('users')->insert(
                [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => \Hash::make($user['password']),
                    'is_admin' => $user['is_admin'],
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                    'api_token' => $user['api_token'],
                ]
            );
        }
    }
}
