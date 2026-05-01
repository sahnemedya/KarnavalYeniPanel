<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class MigrateLegacyData extends Command
{
    protected $signature = 'migrate:legacy-data';
    protected $description = 'Eski Laravel 9 veritabanından Laravel 12 yapısına verileri taşır.';

    public function handle()
    {
        $this->info('Veri aktarımı başlıyor. Lütfen bekleyin...');

        // 1. BAĞIMSIZ TABLOLAR (Ebeveynler - İlk aktarılacaklar)

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $this->info('Hedef tablolar temizleniyor (Truncate)...');
            DB::table('languages')->truncate();
            DB::table('karnaval_sezonus')->truncate();
            DB::table('roles')->truncate();
            // DB::table('users')->truncate(); // Eğer superadmin hesabının silinmesini istemiyorsan users'ı truncate etme!
            DB::table('categories')->truncate();
            DB::table('pages')->truncate();
            DB::table('f_a_q_s')->truncate();
            DB::table('galleries')->truncate();
            DB::table('site_settings')->truncate();

            $this->info('1. Diller Aktarılıyor...');
            DB::connection('mysql_old')->table('dils')->orderBy('id')->chunk(100, function ($diller) {
                foreach ($diller as $dil) {
                    DB::table('languages')->updateOrInsert(
                        ['id' => $dil->id],
                        ['name' => $dil->adi, 'code' => $dil->dil_kodu, 'active' => 1]
                    );
                }
            });

            $this->info('2. Karnaval Sezonları Aktarılıyor...');
            DB::connection('mysql_old')->table('karnaval_bilgisis')->orderBy('id')->chunk(100, function ($sezonlar) {
                foreach ($sezonlar as $sezon) {
                    DB::table('karnaval_sezonus')->updateOrInsert(
                        ['id' => $sezon->id],
                        [
                            'karnaval_tarihi_baslangic' => $sezon->karnaval_tarihi_baslangic,
                            'karnaval_tarihi_bitis' => $sezon->karnaval_tarihi_bitis,
                            'sezon_baslangici' => $sezon->sezon_baslangici,
                            'karnaval_yili' => $sezon->karnaval_yili,
                            'published' => $sezon->aktif ?? 0
                        ]
                    );
                }
            });

            $this->info('3. Roller ve Yöneticiler (Admins -> Users) Aktarılıyor...');
            DB::connection('mysql_old')->table('roles')->orderBy('id')->chunk(100, function ($roles) {
                foreach ($roles as $role) {
                    DB::table('roles')->updateOrInsert(
                        ['id' => $role->id],
                        ['name' => $role->role, 'label' => \Str::slug($role->role)]
                    );
                }
            });

            DB::connection('mysql_old')->table('admins')->orderBy('id')->chunk(100, function ($admins) {
                foreach ($admins as $admin) {
                    DB::table('users')->updateOrInsert(
                        ['id' => $admin->id],
                        ['name' => $admin->name, 'email' => $admin->email, 'password' => $admin->password]
                    );
                }
            });

            // 2. BAĞIMLI TABLOLAR (Kategori, Sayfa, Galeri vb.)

            $this->info('4. Kategoriler Aktarılıyor...');
            DB::connection('mysql_old')->table('kategoris')->orderBy('id')->chunk(100, function ($kategoriler) {
                foreach ($kategoriler as $kat) {
                    DB::table('categories')->updateOrInsert(
                        ['id' => $kat->id],
                        [
                            'name' => $kat->kategori_adi,
                            'image' => $kat->resim,
                            'hit' => $kat->hit,
                            'parent_category' => $kat->ust_kategori_id,
                            'lang_id' => $kat->dil_id,
                            'show_menu' => $kat->navbardaGoster,
                            'show_homepage' => $kat->anasayfadaGoster,
                            'show_footer' => $kat->footerdaGoster
                        ]
                    );
                }
            });

            $this->info('5. Sayfalar Aktarılıyor...');
            DB::connection('mysql_old')->table('sayfas')->orderBy('id')->chunk(100, function ($sayfalar) {
                foreach ($sayfalar as $sayfa) {
                    DB::table('pages')->updateOrInsert(
                        ['id' => $sayfa->id],
                        [
                            'title' => $sayfa->baslik,
                            'slug' => $sayfa->slug,
                            'content' => $sayfa->icerik,
                            'image' => $sayfa->vitrin_resim,
                            'category_id' => $sayfa->ust_birim,
                            'sezon_id' => $sayfa->karnaval_sezonu,
                            'lang_id' => $sayfa->dil_id,
                            'video' => $sayfa->youtubeplaylist
                        ]
                    );
                }
            });

            $this->info('6. Sıkça Sorulan Sorular Aktarılıyor...');
            DB::connection('mysql_old')->table('sikca_sorulan_sorulars')->orderBy('id')->chunk(100, function ($sss) {
                foreach ($sss as $s) {
                    DB::table('f_a_q_s')->updateOrInsert(
                        ['id' => $s->id],
                        ['question' => $s->soru, 'answer' => $s->cevap, 'page_id' => $s->sayfa_id, 'lang_id' => $s->dil_id, 'hit' => $s->hit]
                    );
                }
            });

            $this->info('7. Galeriler Aktarılıyor...');
            DB::connection('mysql_old')->table('galeris')->orderBy('id')->chunk(100, function ($galeriler) {
                foreach ($galeriler as $galeri) {
                    DB::table('galleries')->updateOrInsert(
                        ['id' => $galeri->id],
                        ['image' => $galeri->resim, 'page_id' => $galeri->sayfa_id]
                    );
                }
            });

            // 3. AYARLAR VE DİĞER MODÜLLER
            $this->info('8. Site Ayarları ve Çeşitler (Blades) Aktarılıyor...');

            DB::connection('mysql_old')->table('ayarlars')->orderBy('id')->chunk(100, function ($ayarlar) {
                foreach ($ayarlar as $ayar) {
                    DB::table('site_settings')->updateOrInsert(
                        ['id' => $ayar->id],
                        [
                            'site_name' => $ayar->site_adi,
                            'seo_title' => $ayar->seo_title,
                            'description' => $ayar->seo_description,
                            'logo' => $ayar->logo,
                            'favicon' => $ayar->favicon,
                            'footer_logo' => $ayar->footer_logo,
                            'head_code' => $ayar->head_ek,
                            'footer_code' => $ayar->footer_ek
                        ]
                    );
                }
            });

            // İptal edilen / Oluşturulmayacak tablolar (kategori_ozelligis, konulars, menu_ayarlaris vb.) bilerek es geçilmiştir.

            $this->info('Veri aktarımı başarıyla tamamlandı!');
        } catch (\Exception $e) {
            $this->error('Bir hata oluştu: ' . $e->getMessage());
        } finally {
            // 2. KRİTİK HAMLE: İşlem bitince veya hata alınca veritabanı güvenliği için kontrolleri tekrar açıyoruz.
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
