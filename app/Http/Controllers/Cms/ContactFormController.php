<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\ContactForm;
use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    public function iletisimFormu()
    {
        $gelenMailler = ContactForm::orderBy('id', 'desc')->get();
        return view('cms.forms.contactForms', compact('gelenMailler'));
    }

    public function getMail(Request $request)
    {
        $mail = ContactForm::find($request->id);

        if ($mail != NULL) {
            $mail->markRead = 1;
            if ($mail->save()) {
                $icerik = '
                        <h2>' . $mail->adSoyad . ' Tarafından Gelen Mail</h2>
                        <table>
                            <tr>
                                <td>Gönderilen Birim:</td>
                                <td>' . $mail->birim . '</td>
                            </tr>
                            <tr>
                                <td>Gönderen:</td>
                                <td>' . $mail->adSoyad . '</td>
                            </tr>
                            <tr>
                                <td>Telefon:</td>
                                <td>' . $mail->telefon . '</td>
                            </tr>
                            <tr>
                                <td>E-mail:</td>
                                <td>' . $mail->email . '</td>
                            </tr>';

                                        if (!empty($mail->konu)) {
                                            $icerik .= '
                            <tr>
                                <td>Konu:</td>
                                <td>' . $mail->konu . '</td>
                            </tr>';
                                        }

                                        if (!empty($mail->mesaj)) {
                                            $icerik .= '
                            <tr>
                                <td>Mesaj:</td>
                                <td>' . $mail->mesaj . '</td>
                            </tr>';
                                        }

                                        if (!empty($mail->cv)) {
                                            $icerik .= '
                            <tr>
                                <td>Cv:</td>
                                <td>' . $mail->cv . '</td>
                            </tr>';
                                        }

                                        if (!empty($mail->hizmet)) {
                                            $icerik .= '
                            <tr>
                                <td>Hizmet:</td>
                                <td>' . $mail->hizmet . '</td>
                            </tr>';
                                        }

                                        if (!empty($mail->tarih)) {
                                            $icerik .= '
                            <tr>
                                <td>Randevu Tarih:</td>
                                <td>' . $mail->tarih . '</td>
                            </tr>';
                                        }

                                        if (!empty($mail->saat)) {
                                            $icerik .= '
                            <tr>
                                <td>Randevu Saat:</td>
                                <td>' . $mail->saat . '</td>
                            </tr>';
                                        }

                                        $icerik .= '
                            <tr>
                                <td>Tarih</td>
                                <td>' . $mail->created_at . '</td>
                            </tr>
                        </table>
                        ';

                return response()->json(['durum' => ' ' . $icerik]);
            }
        }
    }
}
