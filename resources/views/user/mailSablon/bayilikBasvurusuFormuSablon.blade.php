<table style="font-family:Arial,Helvetica,sans-serif;background:#EEE;border:1px solid #CCC;" width="440" border="0"
       align="center" cellpadding="3" cellspacing="0">

    <table style="border:1px solid #ccc;border-collapse:collapse;" width="440" border="1" cellspacing="3" align="center"
           cellpadding="5">
        <thead>
        <tr>
            <td style="background:#0b1b28;color:#f5f5f5;padding:10px;" colspan="2">
                <h1 style="color:#f5f5f5;font-family:Arial,Helvetica,sans-serif;font-size:12pt;margin:0;">
                    <center> Bayilik Başvuru Formu | {{env('APP_NAME')}}</center>
                </h1>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Ad Soyad:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['kullaniciAdSoyad']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Telefon:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><span
                    class="wmi-callto">{{$data['kullaniciTelefon']}}</span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">E-Posta:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><a
                    href="mailto:{{$data['kullaniciEmail']}}">{{$data['kullaniciEmail']}}</a></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">İl:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><span
                    class="wmi-callto">{{$data['kullaniciBayilikIl']}}</span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">İlçe:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><span
                    class="wmi-callto">{{$data['kullaniciBayilikIlce']}}</span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right"><small>KVKK Onayı:</small></td>
            <td style="font-size:9pt;background-color:#fff;"><span
                    class="wmi-callto"><small>{{$data['kullaniciKvkkOnayi']}}</small></span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Mesaj:
            </td>
            <td style="font-size:9pt;background-color:#fff;">{{$data['kullaniciMesaj']}}<br>{{$data['kullaniciTarih']}}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;font-weight:bold;color:#f00;">
                UYARI:Lütfen bu maili yanıtlamayınız. Formu gönderen kişiye mail atmak için Eposta bölümünde yazan mail
                adresine mail gönderiniz
            </td>
        </tr>
        </tbody>
    </table>
</table>

