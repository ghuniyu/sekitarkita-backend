<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class NovaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sekitarkita.id',
            'password' => '$2y$10$KoGXJOk4Q.ry5O55MJ4wU.g1sXYfBZt4rD3jHevB20ip.GRbG00cO'
        ]);
    }
}
