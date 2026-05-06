<?php

namespace App\Http\Controllers\Cms;


use App\Http\Controllers\Controller;
use App\Models\BultenIletisimFormu;
use Illuminate\Http\Request;


class BultenIletisimFormController extends Controller
{
    public function bultenFormu()
    {
        // Model ismini 'BultenFormu' olarak varsaydım, kendi modelinle değiştirmeyi unutma
        $gelenMailler = BultenIletisimFormu::orderBy('id','desc')->get();

        // View yolunu bülten klasörüne göre ayarladım
        return view('cms.forms.bultenFormu', compact('gelenMailler'));
    }

    public function getBultenDetay(Request $request)
    {
        // Model ismini güncelle
        $mail = BultenIletisimFormu::find($request->id);

        if ($mail != NULL) {
            $mail->markRead = 1;
            if ($mail->save()) {
                $icerik = '
            <h2>'. $mail->adSoyad . ' Tarafından Gelen Bülten Talebi</h2>
            <table class="table table-bordered">
                    <tr>
                        <td><strong>Gönderilen Birim:</strong></td>
                        <td>' . $mail->birim . '</td>
                    </tr>
                    <tr>
                        <td><strong>Gönderen:</strong></td>
                        <td>' . $mail->adSoyad . '</td>
                    </tr>
                    <tr>
                        <td><strong>Telefon:</strong></td>
                        <td>' . $mail->telefon . '</td>
                    </tr>
                    <tr>
                        <td><strong>E-mail:</strong></td>
                        <td>' . $mail->email . '</td>
                    </tr>
                    <tr>
                        <td><strong>Konu:</strong></td>
                        <td>' . $mail->konu . '</td>
                    </tr>
                    <tr>
                        <td><strong>Mesaj:</strong></td>
                        <td>' . $mail->mesaj . '</td>
                    </tr>
                    <tr>
                        <td><strong>Tarih:</strong></td>
                        <td>' . $mail->created_at . '</td>
                    </tr>
            </table>
            ';
                return response()->json(['durum' => $icerik]);
            }
        }
    }

}
