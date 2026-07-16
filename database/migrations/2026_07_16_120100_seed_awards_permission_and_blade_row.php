<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Ödüller modülü için gerekli tek seferlik veri kayıtları:
 * - permissions tablosuna "oduller" yetki satırı (panel sidebar @permission direktifi için)
 * - blades tablosuna "oduller.blade.php" şablon satırı (Sayfa oluştururken seçilebilsin diye)
 * Bu migration hiçbir tabloyu silmez/değiştirmez, sadece ekleme (insert) yapar.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::table('permissions')->updateOrInsert(
            ['label' => 'oduller'],
            ['name' => 'Ödüller', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );

        DB::table('blades')->updateOrInsert(
            ['file' => 'oduller.blade.php'],
            ['name' => 'Ödüller Sayfası', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        );
    }

    public function down(): void
    {
        DB::table('permissions')->where('label', 'oduller')->delete();
        DB::table('blades')->where('file', 'oduller.blade.php')->delete();
    }
};
