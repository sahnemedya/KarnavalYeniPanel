<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\BalkonVitrinBasvuru;
use Illuminate\Http\Request;

class BalkonVitrinBasvuruController extends Controller
{
    public function yarismaBasvurulari()
    {
        $gelenMailler = BalkonVitrinBasvuru::orderBy('id','desc')->get();

        return view('cms.forms.yarismaBasvurularFormu', compact('gelenMailler'));
    }

    public function getYarismaDetay(Request $request)
    {
        $mail = BalkonVitrinBasvuru::find($request->id);

        if ($mail != NULL) {
            $mail->markRead = 1;
            if ($mail->save()) {

                $fotoHtml = '';

                // Veritabanından gelen veriyi kontrol et
                // Eğer Model'de $casts tanımlıysa array gelir, değilse string gelir.
                $fotos = $mail->fotograflar;
                if (!is_array($fotos)) {
                    // Eğer string gelirse (virgüllü veya JSON), array'e çeviriyoruz
                    $fotos = json_decode($fotos, true) ?: explode(',', $fotos);
                }

                if(!empty($fotos) && is_array($fotos)) {
                    foreach($fotos as $f) {
                        $f = trim($f); // Boşlukları temizle
                        if($f != "") {
                            $fotoHtml .= '<a href="'.asset('storage/'.$f).'" target="_blank">
                                        <img src="'.asset('storage/'.$f).'" style="width:100px; height:100px; object-fit:cover; margin:5px; border:1px solid #ddd;">
                                      </a>';
                        }
                    }
                }

                $veliBelgeLink = $mail->veli_izin_belgesi
                    ? '<a href="'.asset('storage/'.$mail->veli_izin_belgesi).'" target="_blank" class="btn btn-sm btn-info">Belgeyi Aç</a>'
                    : 'Yok (Reşit)';

                // Migration'daki sütun isimlerine göre güncellendi (ad_soyad)
                $icerik = '
            <h2>'. $mail->ad_soyad . ' Başvuru Detayı</h2>
            <table class="table table-bordered">
                    <tr><td><strong>Kategori:</strong></td><td>' . strtoupper($mail->tur) . '</td></tr>
                    '. ($mail->isletme_adi ? '<tr><td><strong>İşletme Adı:</strong></td><td>' . $mail->isletme_adi . '</td></tr>' : '') .'
                    <tr><td><strong>Ad Soyad:</strong></td><td>' . $mail->ad_soyad . '</td></tr>
                    <tr><td><strong>E-mail:</strong></td><td>' . $mail->email . '</td></tr>
                    <tr><td><strong>Telefon:</strong></td><td>' . $mail->telefon . '</td></tr>
                    <tr><td><strong>Adres:</strong></td><td>' . $mail->adres . '</td></tr>
                    <tr><td><strong>Veli İzin Belgesi:</strong></td><td>' . $veliBelgeLink . '</td></tr>
                    <tr><td><strong>Yüklenen Fotoğraflar:</strong></td><td>' . $fotoHtml . '</td></tr>
                    <tr><td><strong>Tarih:</strong></td><td>' . $mail->created_at->format('d.m.Y H:i') . '</td></tr>
            </table>';

                return response()->json(['durum' => $icerik]);
            }
        }
    }
}
