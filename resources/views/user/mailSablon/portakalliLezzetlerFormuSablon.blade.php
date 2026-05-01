<table style="font-family:Arial,Helvetica,sans-serif;background:#EEE;border:1px solid #CCC;" width="440" border="0"
       align="center" cellpadding="3" cellspacing="0">

    <table style="border:1px solid #ccc;border-collapse:collapse;" width="440" border="1" cellspacing="3" align="center"
           cellpadding="5">
        <thead>
        <tr>
            <td style="background:#ee7204;color:#FFF;padding:10px;" colspan="2">
                <h1 style="color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12pt;margin:0;">
                    <center>Portakallı Lezzetler Başvuru Formu | {{env('APP_NAME')}}</center>
                </h1>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Kategori:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['kategori']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Ad Soyad:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['adsoyad']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">T.C. No:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['tc']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Doğum Tarihi:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['dogumtarihi']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Adres:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['adres']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Telefon:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><span
                        class="wmi-callto">{{$data['telefon']}}</span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">E-Posta:
            </td>
            <td style="font-size:10pt;background-color:#fff;"><a
                        href="mailto:{{$data['mail']}}">{{$data['mail']}}</a></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Meslek:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['meslek']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Yemek Adı:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['yemekAdi']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Yemek Malzemesi:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['yemekMalzemesi']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Yemek Tarifi:
            </td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['yemekTarifi']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right"><small>Bilgilendirme ve Aydınlatma Metni Onay:</small></td>
            <td style="font-size:9pt;background-color:#fff;"><span
                        class="wmi-callto"><small>{{$data['kullaniciKvkkOnayi']}}</small></span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right"><small>Şartname Onay:</small></td>
            <td style="font-size:9pt;background-color:#fff;"><span
                        class="wmi-callto"><small>{{$data['kullaniciSartnameOnayi']}}</small></span></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;"
                align="right">Alt Bilgi:
            </td>
            <td style="font-size:9pt;background-color:#fff;"><br>Tarih: {{$data['kullaniciTarih']}}
                --- Gönderen IP: {{$data['kullaniciIP']}}</td>
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

