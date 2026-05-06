<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\PortakalliLezzetler;
use Illuminate\Http\Request;

class PortakalliLezzetlerFormController extends Controller
{
    public function portakalliLezzetler()
    {
        $gelenMailler = PortakalliLezzetler::orderBy('id', 'desc')->get();
        return view('cms.forms.portakalliLezzetler', compact('gelenMailler'));
    }

    public function getPortakalliLezzetler(Request $request)
    {
        $mail = PortakalliLezzetler::find($request->id);
        if ($mail != NULL) {
            $mail->markRead = 1;
            if ($mail->save()) {
                $icerik = '
                <h2>'. $mail->ad . ' Tarafından Gelen Mail</h2>
                <table>
                        <tr>
                            <td>Ad Soyad:</td>
                            <td>' . $mail->ad . '</td>
                        </tr>
                        <tr>
                            <td>TC:</td>
                            <td>' . $mail->tc . '</td>
                        </tr>
                        <tr>
                            <td>Doğum Tarihi:</td>
                            <td>' . $mail->dogum . '</td>
                        </tr>
                        <tr>
                            <td>Adres:</td>
                            <td>' . $mail->adres . '</td>
                        </tr>
                        <tr>
                            <td>Telefon:</td>
                            <td>' . $mail->telefon . '</td>
                        </tr>
                        <tr>
                            <td>Mail:</td>
                            <td>' . $mail->mail . '</td>
                        </tr>
                        <tr>
                            <td>Meslek:</td>
                            <td>' . $mail->meslek . '</td>
                        </tr>
                        <tr>
                            <td>Yemek Adı:</td>
                            <td>' . $mail->yemekadi . '</td>
                        </tr>
                        <tr>
                            <td>Yemek Malzemesi:</td>
                            <td>' . $mail->yemekmalzemesi . '</td>
                        </tr>
                        <tr>
                            <td>Yemek Tarifi:</td>
                            <td>' . $mail->yemektarifi . '</td>
                        </tr>
                        <tr>
                            <td>Bilgi:</td>
                            <td>' . $mail->bilgi . '</td>
                        </tr>


                </table>
        ';
                return response()->json(['durum' => '' . $icerik]);
            }
        }


    }
}
