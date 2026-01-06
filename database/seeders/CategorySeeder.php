<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Электроника', 'slug' => 'electronics'],
            ['name' => 'Бытовая техника', 'slug' => 'appliances'],
            ['name' => 'Одежда', 'slug' => 'clothing'],
            ['name' => 'Красота', 'slug' => 'beauty'],
            ['name' => 'Дом', 'slug' => 'home'],
            ['name' => 'Спорт', 'slug' => 'sports'],
            ['name' => 'Дети', 'slug' => 'kids'],
            ['name' => 'Еда', 'slug' => 'food'],
            ['name' => 'Авто', 'slug' => 'automotive'],
            ['name' => 'Книги', 'slug' => 'books']
        ];

        foreach ($categories as $category)
        {
            Category::firstOrCreate(
                [
                    'slug' => $category['slug'],
                ],
                [
                    'name' => $category['name'],
                ]
            );
        }

    }
}
