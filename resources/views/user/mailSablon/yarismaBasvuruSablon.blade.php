<table style="font-family:Arial,Helvetica,sans-serif;background:#EEE;border:1px solid #CCC;" width="440" border="0" align="center" cellpadding="3" cellspacing="0">
    <table style="border:1px solid #ccc;border-collapse:collapse;" width="440" border="1" cellspacing="3" align="center" cellpadding="5">
        <thead>
        <tr>
            <td style="background:#ee7204;color:#FFF;padding:10px;" colspan="2">
                <h1 style="color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:12pt;margin:0;">
                    <center>{{ strtoupper($data['tur']) }} - Yarışma Başvuru Formu | {{env('APP_NAME')}}</center>
                </h1>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">Ad Soyad:</td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['kullaniciAdSoyad']}}</td>
        </tr>
        @if($data['isletme_adi'])
            <tr>
                <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">İşletme:</td>
                <td style="font-size:10pt;background-color:#fff;">{{$data['isletme_adi']}}</td>
            </tr>
        @endif
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">Telefon:</td>
            <td style="font-size:10pt;background-color:#fff;">{{$data['kullaniciTelefon']}}</td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">E-Posta:</td>
            <td style="font-size:10pt;background-color:#fff;"><a href="mailto:{{$data['kullaniciEmail']}}">{{$data['kullaniciEmail']}}</a></td>
        </tr>
        <tr>
            <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">Fotoğraflar:</td>
            <td style="font-size:10pt;background-color:#fff;">
                @foreach($data['fotograflar'] as $key => $foto)
                    <a href="{{ asset('storage/' . $foto) }}">Foto {{ $key + 1 }}</a>{{ !$loop->last ? ',' : '' }}
                @endforeach
            </td>
        </tr>
        @if($data['veli_belgesi'])
            <tr>
                <td style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;width:100px;font-weight:bold;color:#4E4E4E;" align="right">Veli Belgesi:</td>
                <td style="font-size:10pt;background-color:#fff;"><a href="{{ asset('storage/' . $data['veli_belgesi']) }}">Belgeyi Gör</a></td>
            </tr>
        @endif
        <tr>
            <td colspan="2" style="font-family:Arial,Helvetica,sans-serif;font-size:10pt;font-weight:bold;color:#f00;">
                UYARI: Lütfen bu maili yanıtlamayınız. Formu gönderen kişiye ulaşmak için yukarıdaki e-posta adresini kullanınız.
            </td>
        </tr>
        </tbody>
    </table>
</table>
