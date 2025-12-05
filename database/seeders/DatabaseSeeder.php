<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $this->createAdminUser();
        
        // Seed all site content
        $this->call([
            SeoSeeder::class,
            SiteContentSeeder::class,
        ]);
    }

    /**
     * Create the default admin user for Filament
     */
    protected function createAdminUser(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@maryslacencraft.com');
        $password = env('ADMIN_PASSWORD', 'Password123');
        
        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Mary Admin',
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );
        
        $this->command->info("✅ Admin user created: {$email}");
    }
}
