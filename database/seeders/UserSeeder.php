<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user and assign the 'admin' role
        $adminRole = Role::where('name', 'admin')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Never use plain passwords like this in production
        ]);

        $admin->roles()->attach($adminRole);  // Assign role to user
        $adminToken = $admin->createToken('AdminToken')->plainTextToken;

        // You can return or store the token for reference
        echo "Admin Token: " . $adminToken . "\n";

        // Create a regular user and assign the 'user' role
        $userRole = Role::where('name', 'user')->first();

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->roles()->attach($userRole);  // Assign role to user
        $userToken = $user->createToken('UserToken')->plainTextToken;

        // You can return or store the token for reference
        echo "User Token: " . $userToken . "\n";
    }
}
