<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::updateOrCreate(
            ['id' => 1],
            [
                "name" => "Özel Menüler",
                "label" => "ozel-menuler"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 2],
            [
                "name" => "Kullanıcılar",
                "label" => "kullanıcılar"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 3],
            [
                "name" => "Roller",
                "label" => "roller"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 4],
            [
                "name" => "Yetkiler",
                "label" => "yetkiler"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 5],
            [
                "name" => "İzinler",
                "label" => "izinler"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 6],
            [
                "name" => "Yetki İzinleri",
                "label" => "yetki-izinleri"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 7],
            [
                "name" => "Ayarlar",
                "label" => "ayarlar"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 8],
            [
                "name" => "Blade",
                "label" => "blade"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 9],
            [
                "name" => "Dil",
                "label" => "dil"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 10],
            [
                "name" => "Kategori",
                "label" => "kategori"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 11],
            [
                "name" => "Sayfa",
                "label" => "sayfa"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 12],
            [
                "name" => "Slider",
                "label" => "slider"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 13],
            [
                "name" => "Galeri",
                "label" => "galeri"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 14],
            [
                "name" => "Referanslar",
                "label" => "referanslar"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 15],
            [
                "name" => "Sertifikalar",
                "label" => "sertifikalar"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 16],
            [
                "name" => "Basın Kiti",
                "label" => "basin-kiti"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 17],
            [
                "name" => "Popup",
                "label" => "popup"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 18],
            [
                "name" => "Seo",
                "label" => "seo"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 19],
            [
                "name" => "İletişim",
                "label" => "iletisim"
            ]
        );

        Permission::updateOrCreate(
            ['id' => 20],
            [
                "name" => "Doktorlar",
                "label" => "doktorlar"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 21],
            [
                "name" => "SSS",
                "label" => "sss"
            ]
        );
        Permission::updateOrCreate(
            ['id' => 22],
            [
                "name" => "Formlar",
                "label" => "formlar"
            ]
        );

    }
}
