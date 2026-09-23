<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createDefaultAdmin();
    }

    /**
     * Create a default admin user.
     */
    private function createDefaultAdmin(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'middle_name' => null,
            'email' => 'admin@school.com',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
            'status' => 'Active',
            'is_deleted' => 0,
        ]);
    }
}
