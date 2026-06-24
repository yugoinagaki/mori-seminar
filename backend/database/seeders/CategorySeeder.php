<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ニュース用サブカテゴリ
            ['name' => 'テレビ・メディア出演', 'slug' => 'tv-appearance', 'post_type' => 'news', 'order_index' => 1],
            ['name' => '新聞・雑誌掲載',       'slug' => 'print-media',   'post_type' => 'news', 'order_index' => 2],
            ['name' => '講演・シンポジウム',    'slug' => 'lecture',       'post_type' => 'news', 'order_index' => 3],
            ['name' => '論考・著作',            'slug' => 'publication',   'post_type' => 'news', 'order_index' => 4],
            // ブログ用サブカテゴリ
            ['name' => '2期生ブログ', 'slug' => 'blog-2nd-gen', 'post_type' => 'blog', 'order_index' => 1],
            ['name' => '3期生ブログ', 'slug' => 'blog-3rd-gen', 'post_type' => 'blog', 'order_index' => 2],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
