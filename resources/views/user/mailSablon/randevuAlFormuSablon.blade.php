<table style="font-family:Arial,Helvetica,sans-serif;background:#EEE;border:1px solid #CCC;" width="440" border="0"
       align="center" cellpadding="3" cellspacing="0">
    <tbody>
    <tr>
        <td style="background:#575757;color:#FFF;padding:10px;"><h1
                style="color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12pt;margin:0;">{{$data['formAdi']}} Formu | {{env('APP_NAME')}}</h1></td>
    </tr>
    <tr>
        <td>
            <table style="border:1px solid #ccc;border-collapse:collapse;" width="100%" border="1" cellspacing="3"
                   cellpadding="5">
                <tbody>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">Ad Soyad:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;">{{$data['kullaniciAdSoyad']}}</td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">E-Posta:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;"><a href="mailto:{{$data['kullaniciEmail']}}">{{$data['kullaniciEmail']}}</a></td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">Telefon:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;"><span class="wmi-callto">{{$data['kullaniciTelefon']}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">Hizmet:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;"><span class="wmi-callto">{{$data['kullaniciHizmet']}}</span></td>
                </tr>

                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">Randevu Saati:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;"><span class="wmi-callto">{{$data['kullaniciSaat']}}</span></td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">KVKK:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;"><span class="wmi-callto"><small>{{$data['kullaniciKvkkOnayi']}}</small></span></td>
                </tr>
                <tr>
                    <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                        align="right">Gelen Mail Tarihi:
                    </td>
                    <td style="font-size:10pt;background-color:#fff;">{{$data['kullaniciTarih']}}</td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;font-weight:bold;color:#f00;">
                        UYARI:Lütfen bu maili yanıtlamayınız. Formu gönderen kişiye mail atmak için Eposta bölümünde
                        yazan mail adresine mail gönderiniz
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    </tbody>
</table>
