<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content_en' => '<h1>Privacy Policy</h1><p>Content goes here...</p>',
                'content_ar' => '<h1>سياسة الخصوصية</h1><p>المحتوى هنا...</p>',
            ],
            [
                'name' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content_en' => '<h1>Terms & Conditions</h1><p>Content goes here...</p>',
                'content_ar' => '<h1>الشروط والأحكام</h1><p>المحتوى هنا...</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
