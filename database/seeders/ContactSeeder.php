<?php

namespace Database\Seeders;
// database/seeders/ContactSeeder.php

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;

class ContactSeeder extends Seeder
{
    protected string $model = Contact::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // automatically create a user unless you override
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'notes' =>fake()->sentence(),
        ];
    }
}
