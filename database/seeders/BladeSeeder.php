<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BladeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blades')->insert([
            ['name' => 'Normal', 'file' => 'normal.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'İnsan Kaynakları', 'file' => 'insan-kaynaklari.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sertifikalar', 'file' => 'sertifikalar.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Blog', 'file' => 'blog.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Blog Detay', 'file' => 'blog-detay.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Video', 'file' => 'video.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'İletişim', 'file' => 'iletisim.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Randevu Al', 'file' => 'randevu-al.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Listele', 'file' => 'Listele.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Menü Listele', 'file' => 'menu-listele.blade.php', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
