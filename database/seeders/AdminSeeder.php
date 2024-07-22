<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminMail = "demo@demo.com";
        $data = Admin::where('email', $adminMail)->first();

        if (! $data) {
            Admin::insert([
                'name' => 'Super Admin',
                'email' => $adminMail,
                'password' => Hash::make('Aa@123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
