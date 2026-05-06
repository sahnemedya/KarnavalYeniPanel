<table style="font-family:Arial,Helvetica,sans-serif;background:#EEE;border:1px solid #CCC;" width="440" align="center" cellpadding="3" cellspacing="0">

    <table style="border:1px solid #ccc;border-collapse:collapse;" width="440" border="1" cellspacing="3" align="center" cellpadding="5">
        <thead>
        <tr>
            <td style="background:#ee7204;color:#FFF;padding:10px;" colspan="2">
                <h1 style="color:#fff;font-size:12pt;margin:0;text-align:center;">
                    Portakallı Lezzetler Başvuru Formu | {{ config('app.name') }}
                </h1>
            </td>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td align="right"><strong>Kategori:</strong></td>
            <td>{{$data['kategori']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Ad Soyad:</strong></td>
            <td>{{$data['ad']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>T.C. No:</strong></td>
            <td>{{$data['tc']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Doğum Tarihi:</strong></td>
            <td>{{$data['dogum']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Adres:</strong></td>
            <td>{{$data['adres']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Telefon:</strong></td>
            <td>{{$data['telefon']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>E-Posta:</strong></td>
            <td><a href="mailto:{{$data['mail']}}">{{$data['mail']}}</a></td>
        </tr>

        <tr>
            <td align="right"><strong>Meslek:</strong></td>
            <td>{{$data['meslek']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Yemek Adı:</strong></td>
            <td>{{$data['yemekadi']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Yemek Malzemesi:</strong></td>
            <td>{{$data['yemekmalzemesi']}}</td>
        </tr>

        <tr>
            <td align="right"><strong>Yemek Tarifi:</strong></td>
            <td>{{$data['yemektarifi']}}</td>
        </tr>

        <tr>
            <td align="right"><small>KVKK Onay:</small></td>
            <td><small>{{ $data['kvkk'] ?? 'Onaylanmadı' }}</small></td>
        </tr>

        <tr>
            <td align="right"><small>Şartname Onay:</small></td>
            <td><small>{{ $data['sartname'] ?? 'Onaylanmadı' }}</small></td>
        </tr>

        <tr>
            <td align="right"><strong>Alt Bilgi:</strong></td>
            <td>
                Tarih: {{$data['kullaniciTarih']}} <br>
                IP: {{$data['kullaniciIP']}}
            </td>
        </tr>

        <tr>
            <td colspan="2" style="font-size:10pt;font-weight:bold;color:#f00;">
                UYARI: Lütfen bu maili yanıtlamayınız. Kullanıcıya ulaşmak için e-posta adresini kullanınız.
            </td>
        </tr>

        </tbody>
    </table>
</table>
