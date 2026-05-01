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
        Category::updateOrCreate(
            ['id' => 1], // eşleşme kriteri
            [
                "name" => "Kurumsal",
                "show_panel" => 1,
                "lang_id" => 1,
                "hit" =>2,
                "is_medical_unit" => 0,
                "is_doctors" => 0
            ]
        );Category::updateOrCreate(
            ['id' => 2], // eşleşme kriteri

            [
                "name" => "Medya",
                "show_panel" => 0,
                "lang_id" => 1,
                "hit" =>10,
                "is_medical_unit" => 0,
                "is_doctors" => 0
            ]

        );
        Category::updateOrCreate(
            ['id' => 3], // eşleşme kriteri
            [
                "name" => "İletişim",
                "show_panel" => 0,
                "lang_id" => 1,
                "hit" =>12,
                "is_medical_unit" => 0,
                "is_doctors" => 0
            ]
        );
    }
}
