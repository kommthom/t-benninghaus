<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            // ->has(Passkey::factory(3))
            ->count(10)
            ->create();

        // The data of the first member is processed separately
        $user = User::query()->find(1);
        $user->update([
            'name' => 'Thomas',
            'email' => 'tbenninghaus@web.de',
        ]);
    }
}
