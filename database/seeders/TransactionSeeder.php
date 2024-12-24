<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Use Faker to generate fake data
        $faker = Faker::create();

        // Get all user IDs to link transactions to a random user
        $userIds = User::pluck('id')->toArray();

        // Create 10 sample transactions
        foreach (range(1, 10) as $index) {
            Transaction::create([
                'transaction_id' => 'TX' . $faker->unique()->numberBetween(1000, 9999), // Unique transaction_id
                'amount' => $faker->randomFloat(2, 10, 1000), // Random amount between 10 and 1000
                'status' => $faker->randomElement(['pending', 'completed', 'cancelled']), // Random status
                'transaction_type' => $faker->randomElement(['deposit', 'withdrawal']), // Random transaction type
                'user_id' => $faker->randomElement($userIds), // Random user ID
            ]);
        }
    }
}
