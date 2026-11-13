<?php

namespace App\Console\Commands;

use App\Models\KarnavalSezonu;
use App\Services\KarnavalSezonuService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Yeni bir karnaval sezonu eklemek için hızlı komut.
 *
 * Kullanım:
 *   php artisan karnaval:yeni-sezon
 *     → Sistemde kayıtlı en büyük yıldan bir sonraki yılı ekler
 *   php artisan karnaval:yeni-sezon 2027
 *     → Belirli bir yılı ekler
 *   php artisan karnaval:yeni-sezon 2027 --aktif
 *     → Ekledikten sonra yeni sezonu aktif eder (diğerleri pasife çekilir)
 *   php artisan karnaval:yeni-sezon 2027 --force
 *     → Soft-deleted ya da aynı yıl mevcutsa üstüne yazarak yeniler
 *
 * Tarih mantığı:
 *   Son kayıtlı sezonun tarihlerine tam 1 yıl ekleyerek yeni tarihleri üretir.
 *   Böylece karnaval tarih kalıbı (ör. Nisan başı) korunur. Elle güncellenmesi
 *   gereken detaylar panel > Karnaval Sezonu > Düzenle'den yapılabilir.
 */
class AddKarnavalSezonu extends Command
{
    protected $signature = 'karnaval:yeni-sezon
                            {yil? : Eklenecek karnaval yılı (boş bırakılırsa son yıl + 1)}
                            {--aktif : Ekledikten sonra bu sezonu aktif yapar}
                            {--force : Aynı yıl varsa / soft-deleted ise üstüne yaz}';

    protected $description = 'Yeni bir karnaval sezonu kaydı oluşturur (varsayılan: son yıl + 1).';

    public function handle(KarnavalSezonuService $service): int
    {
        $hedefYil = $this->argument('yil') ? (int) $this->argument('yil') : null;

        // --force verildiyse ve aynı yıl mevcutsa önce sil / restore et
        if ($this->option('force') && $hedefYil) {
            $mevcut = KarnavalSezonu::withTrashed()
                ->where('karnaval_yili', (string) $hedefYil)
                ->first();

            if ($mevcut) {
                if ($mevcut->trashed()) {
                    $mevcut->forceDelete();
                } else {
                    $mevcut->forceDelete();
                }
                $this->warn("'{$hedefYil}' yılının eski kaydı silindi (--force).");
            }
        }

        $sonuc = $service->sonrakiYiliEkle($hedefYil, $this->option('aktif'));

        if ($sonuc['status'] === 'success') {
            $yeni = $sonuc['data'];
            $this->info($sonuc['message']);
            $this->newLine();
            $this->line("  Yıl                           : {$yeni->karnaval_yili}");
            $this->line("  Sezon başlangıcı              : {$yeni->sezon_baslangici}");
            $this->line("  Karnaval tarihi (başlangıç)   : {$yeni->karnaval_tarihi_baslangic}");
            $this->line("  Karnaval tarihi (bitiş)       : {$yeni->karnaval_tarihi_bitis}");
            $this->line("  Sıralama (hit)                : {$yeni->hit}");
            $this->line("  Aktif?                        : " . ($yeni->published ? 'EVET' : 'hayır'));
            $this->newLine();
            $this->info('Not: Tarihler son sezon kalıbından türetildi. Panel > Karnaval Sezonu > Düzenle ile kesin tarihleri girebilirsin.');
            return self::SUCCESS;
        }

        if ($sonuc['status'] === 'warning') {
            $this->warn($sonuc['message']);
            $this->line('Yeniden oluşturmak için: php artisan karnaval:yeni-sezon ' . ($hedefYil ?? '') . ' --force');
            return self::FAILURE;
        }

        $this->error($sonuc['message']);
        return self::FAILURE;
    }
}
