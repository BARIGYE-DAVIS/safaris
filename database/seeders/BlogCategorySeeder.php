<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder will create a set of common blog categories.
     * It uses firstOrCreate so it is safe to run multiple times.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Technology',
            'Business',
            'Lifestyle',
            'Travel',
            'Health & Wellness',
            'Education',
            'Food & Drink',
            'Opinion',
            'News',
            'Tutorials',
        ];

        foreach ($categories as $index => $name) {
            BlogCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => null,
                    'order' => $index + 1,
                ]
            );
        }
    }
}