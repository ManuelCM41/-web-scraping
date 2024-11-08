<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o buscar el plan 'Free'
        $freeMembership = Membership::firstOrCreate(
            ['plan' => 'Free'],
            ['precio' => '0', 'cantidad_veces' => 3, 'cantidad_urls' => 1]
        );

        // Crear o buscar el plan 'Premium'
        $premiumMembership = Membership::firstOrCreate(
            ['plan' => 'Pro'],
            ['precio' => '10', 'cantidad_veces' => 10, 'cantidad_urls' => 2] // Ajusta el precio y cantidad_veces según tus necesidades
        );

        // Crear o buscar el plan 'Enterprise'
        $enterpriseMembership = Membership::firstOrCreate(
            ['plan' => 'Pro Max'],
            ['precio' => '30', 'cantidad_veces' => 20, 'cantidad_urls' => 5] // Ajusta el precio y cantidad_veces según tus necesidades
        );

        User::create([
            'dni' => '77298042',
            'name' => 'Manuel',
            'surnames' => 'Chunca Mamani',
            'phone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'status' => true,
            'membership_id' => $enterpriseMembership->id,
            'email' => 'manuelchunca04@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Super-admin');

        User::create([
            'dni' => '77298041',
            'name' => 'Frank Grimaldy',
            'surnames' => 'Chunca Mamani',
            'phone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'status' => true,
            'membership_id' => $premiumMembership->id,
            'email' => 'frankchunca@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Administrador');

        User::create([
            'dni' => '77489560',
            'name' => 'Luis Sandro',
            'surnames' => 'Chunca Mamani',
            'phone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'status' => true,
            'membership_id' => $freeMembership->id,
            'email' => 'luischunca@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('Usuario');

        // Crear usuarios adicionales con el rol 'Usuario' y plan 'Free'
        User::factory(18)->create([
            'membership_id' => $freeMembership->id,
        ])->each(function ($user) {
            $user->assignRole('Usuario');
        });
    }
}
