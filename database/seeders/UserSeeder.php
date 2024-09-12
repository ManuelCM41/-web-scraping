<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'dni' => '77298042',
            'name' => 'Manuel',
            'surnames' => 'Chunca Mamani',
            'phone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'status' => true,
            'email' => 'manuelchunca04@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Super-admin');

        User::create([
            'dni' => '77298041',
            'name' => 'Frank Grimaldy',
            'surnames' => 'Chunca Mamani',
            'phone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'status' => true,
            'email' => 'frankchunca@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Administrador');

        $users = User::factory(18)->create();

        foreach ($users as $user) {
            $user->assignRole('Usuario');
        }
    }
}
