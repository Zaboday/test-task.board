<?php

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/* @var EloquentFactory $factory */
$factory->define(
    \App\Models\Message::class, function (Faker $faker) {
        $user = User::where('email', '=', 'not.admin@admin.com')->first();

        return [
        'text' => $faker->text,
        'title' => $faker->text(50),
        'user_id' => $user->id,
    ];
    }
);
