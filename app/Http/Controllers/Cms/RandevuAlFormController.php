<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\RandevuAlForm;
use Illuminate\Http\Request;

class RandevuAlFormController extends Controller
{
    public function randevuAlFormu()
    {
        $gelenMailler = RandevuAlForm::orderBy('id', 'desc')->get();
        return view('cms.forms.randevuAlForms', compact('gelenMailler'));
    }

    public function getRMail(Request $request)
    {
        $mail = RandevuAlForm::find($request->id); // Bu satırda değişiklik yapılmadı
        if ($mail != NULL) {
            $mail->markRead = 1;
            if ($mail->save()) {

                // --- HTML İçerik (Tablo) Güncellendi ---
                $icerik = '
            <h2>'. $mail->adSoyad . ' Tarafından Gelen Mail</h2>
            <table>
                    <tr>
                        <td>Gönderen:</td>
                        <td>' . $mail->adSoyad . '</td>
                    </tr>
                    <tr>
                        <td>E-Posta:</td>
                        <td>' . $mail->email . '</td>
                    </tr>
                    <tr>
                        <td>Telefon:</td>
                        <td>' . $mail->telefon . '</td>
                    </tr>
                    <tr>
                        <td>Klinik:</td>
                        <td>' . $mail->birim . '</td>
                    </tr>
                    <tr>
                        <td>Seçtiği Doktor:</td>
                        <td>' . $mail->doktor . '</td>
                    </tr>
                    <tr>
                        <td>Saat:</td>
                        <td>' . $mail->saat . '</td>
                    </tr>
                    <tr>
                        <td>Tarih:</td>
                        <td>' . $mail->tarih . '</td>
                    </tr>
                    <tr>
                        <td>Mesaj:</td>
                        <td>' . $mail->mesaj . '</td>
                    </tr>
                    <tr>
                        <td>Oluşturma Tarihi:</td>
                        <td>' . $mail->created_at . '</td>
                    </tr>
            </table>
    ';
                // --- HTML İçerik Güncellemesi Bitti ---

                return response()->json(['durum' => '' . $icerik]);
            }
        }


    }
}
