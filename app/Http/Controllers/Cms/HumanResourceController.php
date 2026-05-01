<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\HumanResourceForm;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    public function insanKaynaklariFormu()
    {
        $gelenMailler = HumanResourceForm::orderBy('id', 'desc')->get();
        return view('cms.forms.humanResourcesForms', compact('gelenMailler'));
    }

    public function getIkMail(Request $request)
    {
        $mail = HumanResourceForm::find($request->id);

        if ($mail != NULL) {
            $mail->markRead = 1;

            if ($mail->save()) {
                $icerik = '
                    <h2>İnsan Kaynakları Formu - ' . $mail->adSoyad . ' - Tarafından Gelen Mail.</h2>
<table>
                        <tr>
                            <td>Ad Soyad:</td>
                            <td>' . $mail->adSoyad . '</td>
                        </tr>
                        <tr>
                            <td>E-mail:</td>
                            <td>' . $mail->email . '</td>
                        </tr>
                        <tr>
                            <td>Telefon:</td>
                            <td>' . $mail->telefon . '</td>
                        </tr>
                        <tr>
                            <td>Birim:</td>
                            <td>' . $mail->birim . '</td>
                        </tr>
                        <tr>
                            <td>Cv:</td>
                            <td>' . $mail->cv . '</td>
                        </tr>
                        <tr>
                            <td>Mesaj:</td>
                            <td>' . $mail->mesaj . '</td>
                        </tr>

                        <tr>
                            <td>Cv İndir:</td>
                            <td><a href="/ik-cv/' . $mail->cvAdi . '" download="/ik-cv/' . $mail->cvAdi . '" class="text-danger"><i class="las la-file-download text-danger"></i> İndir</a></td>
                        </tr>
                        <tr>
                            <td>Bilgi:</td>
                            <td>Tarih - Saat: ' . $mail->created_at . '</td>
                        </tr>
                    </table>


                ';
                return response()->json(['durum' => '' . $icerik]);
            }
        }


    }
}
