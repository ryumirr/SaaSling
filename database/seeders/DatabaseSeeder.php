<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $account = Account::factory()->create(['name' => 'Test Account']);

        User::factory()->create([
            'name'       => 'Test User',
            'email'      => 'test@example.com',
            'account_id' => $account->id,
        ]);
    }
}
