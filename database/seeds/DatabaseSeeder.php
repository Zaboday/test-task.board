<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        factory(App\Models\User::class, 10)->create()->each(
            function (User $u) {
                $u->messages()->save(factory(App\Models\Message::class)->make());
            }
        );
    }
}
