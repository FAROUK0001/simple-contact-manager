<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'farouk',                                // fixed name
            'email' => 'f@gmail.com',                          // fixed email
            'password' => Hash::make('00000000'),              // fixed password
        ]);

        Contact::factory()->count(50)->create([
            'user_id' => $user->id,
        ]);

    }
}
