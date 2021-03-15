<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testEmails = [
            'test1@endiaries.test',
            'test2@endiaries.test',
            'test3@endiaries.test',
            'test4@endiaries.test',
        ];

        foreach ($testEmails as $email) {
            User::firstOrCreate([
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('secret'),
            ]);
        }
    }
}
