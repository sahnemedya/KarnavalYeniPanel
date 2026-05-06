@extends('user.partial.master')
@section('content')
    <style>
        /* Arka plan karartması */
        .modal-overlay {
            display: none; /* Varsayılan olarak gizli */
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Siyah transparan */
        }

        /* Modal Kutusu */
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* Üstten %10 boşluk ve ortala */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Mobil uyumlu genişlik */
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Kapatma (X) Butonu */
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* İçerik Kaydırma Alanı */
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
            line-height: 1.6;
        }

        /* Alt buton */
        .modal-ok-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
    </style>
    <section class="normal-sayfa content-space">
        <div class="max-width ">
            <div class="text">
                <h1 style="letter-spacing: 1px">{{ $page->title }}</h1>
                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif
                <div class="contact-form-container">
                    <form method="post" class="iletisim-formu" action="{{route('portakalliLezzetlerPost')}}">
                        @csrf

                        {{-- Kategori --}}
                        <div class="form-group section-bg item-100">
                            <label class="section-label">Kategori Seçimi (*)</label>
                            <select name="kategori" class="item100" required >
                                <option  selected value>Kategori Seçin</option>
                                <option value="15-23 Yaş Şef Adayı Öğrenciler">15-23 Yaş Şef Adayı Öğrenciler</option>
                                <option value="16-30 Yaş Genç Şefler">16-30 Yaş Genç Şefler</option>
                                <option value="Adana Mutfağı Lezzetleri">Adana Mutfağı Lezzetleri</option>
                            </select>
                        </div>

                        {{-- Kişisel Bilgiler --}}
                        <input type="text" name="adsoyad" placeholder="Ad Soyad" class="item" required >
                        <input type="text" name="tc" placeholder="T.C. Kimlik No:" class="item" required >

                        <input type="date" name="dogumtarihi" placeholder="Doğum Tarihi:" class="item" required >
                        <input type="text" name="adres" placeholder="Adres:" class="item" required >

                        <input type="tel" name="telefon" placeholder="Telefon:" class="item" required >
                        <input type="text" name="meslek" placeholder="Meslek:" class="item" required >

                        <input type="email" name="mail" placeholder="E-posta Adresiniz" class="item100" required >

                        {{-- Yemek Bilgisi --}}
                        <div class="form-group section-bg item-100">
                            <h2>Yarışmaya Katıldığınız Yemek</h2>
                        </div>

                        <input type="text" class="item100" name="yemekAdi" placeholder="Yemeğin Adı:" required >

                        <textarea name="yemekMalzemesi" rows="5"
                                  placeholder="Yemek Malzemeleri (Gramajlı olarak belirtilmeli)"
                                  class="item100"
                                  required ></textarea>

                        <textarea name="yemekTarifi" rows="5"
                                  placeholder="Yemeğin Tarifi"
                                  class="item100"
                                  required ></textarea>

                        {{-- KVKK --}}
                        <div class="kvkk-wrapper item100">
                            <input type="checkbox" name="kvkk" value="1" id="kvkk" required >
                            <label for="kvkk">
                                <a href="#">Bilgilendirme ve Aydınlatma Metni'ni</a> Okudum ve Onaylıyorum.
                            </label>
                        </div>

                        {{-- Şartname --}}
                        <div class="kvkk-wrapper item100">
                            <input type="checkbox" name="sartname" value="1" id="sartname" required >
                            <label for="sartname">
                                <a href="{{asset('portakalli-lezzetler-yarisma-sartnamesi-2025.pdf')}}" target="_blank">
                                    Portakallı Lezzetler Şartnamesini Okudum
                                </a>
                            </label>
                        </div>

                        {{-- Buton --}}
                        <button type="submit" class="submit-btn item100 balkon-btn" >
                            BAŞVUR
                        </button>

                    </form>
                </div>

            </div>
        </div>
        <div id="kvkkModal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Bilgilendirme ve Aydınlatma Metni</h2>
                <div class="modal-body">
                    <h2>İletişim İzni Hakkında KVKK Açık Rıza Metnine İlişkin Bilgilendirme</h2>
                    <p>İşbu metin, 6698 sayılı Kişisel Verilerin Korunması Kanunu 5/1 maddesi kapsamında; Nisan’da
                        Adana’da Turizm Kültür Sanat Vakfı ve Vakfa ait iktisadi işletmeler tarafından sunulan ürün ve
                        hizmetler hakkında
                        genel/özel imkanların duyurulmasına, sponsorluk işlemlerinin teşviki kapsamında bilgilendirme ve
                        hatırlatma yapılmasına, sunulan hizmetlere ilişkin memnuniyet anketi yapılabilmesine, güncel
                        gelişmelere dair bilginin aktarılmasına, taziye/kutlama amaçlı genel/özel iletiler
                        gönderilmesine, sunum ve bülten gibi
                        içeriklerin paylaşılmasına, tanıtım ve reklamın yapılması için ilgili mevzuata uygun olarak
                        ticari elektronik ileti gönderilmesine, iletişim bilgilerimin, kimlik bilgilerimin, iletişim
                        faaliyetlerinin yürütülmesi/ reklam ve tanıtım faaliyetlerinin yürütülmesi/ sosyal sorumluluk ve
                        sivil toplum
                        faaliyetlerinin yürütülmesi adına bu amaçlar ile alındığına ve seçmiş olduğum iletişim kanalı
                        kapsamında elektronik posta ve/veya SMS yolu ile tarafıma iletiler gönderileceğine, hiçbir
                        gerekçe göstermeksizin tarafıma gelen iletilerde belirtilen işlemi reddederek iletişimi
                        durdurabileceğime, bu kapsamda Nisan’da Adana’da Turizm Kültür Sanat Vakfı tarafından ticari
                        elektronik ileti gönderilebilmesi amacıyla kişisel
                        verilerimin işlenmesine ve işbu süreç için gerekli bilgilerin iş ortaklarına aktarılmasına
                        ilişkin onayımı içermektedir.
                    </p>
                </div>
                <button class="modal-ok-btn" onclick="closeModal()">Okudum, Anladım</button>
            </div>
        </div>
    </section>

@endsection

@section('extraJs')
    <script>
        setTimeout(function () {
            var head = document.getElementsByTagName('head')[0];
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.onload = function () {
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{$apiKeys->recaptcha_site_key}}', {
                        action: 'validate_captcha'
                    }).then(function (cevap) {
                        var formelement = document.getElementById('securitytoken');
                        formelement.value = cevap;
                    });
                });
            }
            script.src = "https://www.google.com/recaptcha/api.js?render={{$apiKeys->recaptcha_site_key}}";
            head.appendChild(script);
        }, 3000);
    </script>

    <script>
        // Modalı açan fonksiyon
        function openModal() {
            document.getElementById("kvkkModal").style.display = "block";
            // Sayfanın arkada kaymasını engellemek istersen:
            document.body.style.overflow = "hidden";
        }

        // Modalı kapatan fonksiyon
        function closeModal() {
            document.getElementById("kvkkModal").style.display = "none";
            // Sayfa kaydırmayı geri aç:
            document.body.style.overflow = "auto";
        }

        // Kullanıcı modal dışına (siyah alana) tıklarsa kapat
        window.onclick = function (event) {
            var modal = document.getElementById("kvkkModal");
            if (event.target == modal) {
                closeModal();
            }
        }

        // ... senin mevcut reCaptcha kodların burada devam edebilir ...
        setTimeout(function () {
            // ...
        }, 3000);
    </script>
    <script>
        // Modalı açan fonksiyon
        function openModal() {
            document.getElementById("kvkkModal").style.display = "block";
            // Sayfanın arkada kaymasını engellemek istersen:
            document.body.style.overflow = "hidden";
        }

        // Modalı kapatan fonksiyon
        function closeModal() {
            document.getElementById("kvkkModal").style.display = "none";
            // Sayfa kaydırmayı geri aç:
            document.body.style.overflow = "auto";
        }

        // Kullanıcı modal dışına (siyah alana) tıklarsa kapat
        window.onclick = function (event) {
            var modal = document.getElementById("kvkkModal");
            if (event.target == modal) {
                closeModal();
            }
        }

        // ... senin mevcut reCaptcha kodların burada devam edebilir ...
        setTimeout(function () {
            // ...
        }, 3000);
    </script>
@endsection






