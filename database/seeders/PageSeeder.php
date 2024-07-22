<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['Privacy Policy', 'Terms & Conditions'];

        foreach ($data as  $value) {
            Page::insert([
                'page_name' => $value,
                'page_slug' => \Str::slug($value),
                'page_seo_title' => $value,
                'page_seo_description' => $value,
                'page_description' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
