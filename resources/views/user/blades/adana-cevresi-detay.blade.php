@extends('user.partial.master')
@section('content')
    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>

                @if ($page->content != null)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif

                <div class="gezilecek-yerler-ic">
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/yilankale.jpg') }}" alt="Yılankale">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.yilankale_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.yilankale_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Du8TRMshEdr2ktaR7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/yag-camii.jpg') }}" alt="Yağ Camii">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.yag_camii_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.yag_camii_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Du8TRMshEdr2ktaR7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/varda-koprusu.jpg') }}" alt="Varda Köprüsü">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.varda_koprusu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.varda_koprusu_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/2nnFAUz1NSzqoTGy7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tepebag-hoyugu.jpg') }}" alt="Tepebağ Höyüğü">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.tepebag_hoyugu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.tepebag_hoyugu_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/tg1WaJrMwJUZk7AJA" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tepebag-evleri.jpg') }}" alt="Tepebağ Evleri">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.tepebag_evleri_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.tepebag_evleri_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/njowuQv6tDbVsenQ9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tas-kopru.jpg') }}" alt="Taş Köprü">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.tas_kopru_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.tas_kopru_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/1jsT6ijELnMbXUv98" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tarihi-kiz-lisesi.jpg') }}"
                                alt="Tarihi Kız Lisesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.tarihi_kiz_lisesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.tarihi_kiz_lisesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/pGrWK6BzZJRFuGUz6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/sar-antik-kenti.jpg') }}"
                                alt="Şar Antik Kenti">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.sar_antik_kenti_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.sar_antik_kenti_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/3kQ5RqVuHVmLzrZ16" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/su-sporlari.avif') }}" alt="Su Sporları">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.su_sporlari_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.su_sporlari_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/7833aGUBcWUkiWoZ9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/sinema-muzesi.jpg') }}" alt="Sinema Müzesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.sinema_muzesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.sinema_muzesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/A7RnAL5SeeoLWyMb7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ramazanoglu-konagi.jpg') }}" alt="Ramazanoğlu Konağı">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.ramazanoglu_konagi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.ramazanoglu_konagi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/phtqPtkYgQaCcmaFA" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/misis-koprusu.jpg') }}" alt="Misis Köprüsü">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.misis_koprusu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.misis_koprusu_aciklama')</p>
                        </div>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/kazancilar-carsisi.jpg') }}" alt="Kazancılar Çarşısı">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.kazancilar_carsisi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.kazancilar_carsisi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/xe4QqpfDDE3Q3RoK6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ilter-uzal-tip-ve-dis-hekimligi-muzesi.jpg') }}"
                                alt="İlter Uzal Tıp ve Diş Hekimliği Müzesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.ilter_uzal_muzesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.ilter_uzal_muzesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/HHjRt7JhmCwQsrt19" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/horzum-yaylasi.jpg') }}"
                                alt="Horzum Yaylası">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.horzum_yaylasi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.horzum_yaylasi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/vxic1x45N4ZqD5ER6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/belemedik-yaylasi.jpg') }}"
                                alt="Belemedik Yaylası">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.belemedik_yaylasi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.belemedik_yaylasi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/8v4uJ6xDMD2GWp3s5" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/buyuk-saat.jpg') }}" alt="Büyük Saat">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.buyuk_saat_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.buyuk_saat_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/AAWhhLA1U3S6nmAo8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/bebekli-kilise.jpg') }}" alt="Bebekli Kilise">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.bebekli_kilise_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.bebekli_kilise_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/FQegtxp7DSqk3sEQ8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ataturk-evi-muzesi.jpg') }}" alt="Atatürk Evi Müzesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.ataturk_evi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.ataturk_evi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/nVnNdjLR4QnSQ7888" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ashab-i-kehf-yedi-uyurlar.jpg') }}" alt="Ashab-ı Kehf &amp; Yedi Uyurlar">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.ashab_i_kehf_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.ashab_i_kehf_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Jr2hPfDWMbXxnuZW7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/antik-luvi-kultur-merkezi.jpg') }}"
                                alt="Antik Luvi Kültür Merkezi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.antik_luvi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.antik_luvi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/5ehw5Wjt12NRDsv68" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/anavarza-kalesi.jpg') }}"
                                alt="Anavarza Kalesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.anavarza_kalesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.anavarza_kalesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Liv4ogPew5HbRD1a9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/adana-ulu-camii.jpg') }}"
                                alt="Adana Ulu Camii">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.ulu_camii_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.ulu_camii_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Stk3Mqwhvpg8RExx9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/adana-muzesi.jpg') }}"
                                alt="Adana Müzesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_adana.adana_muzesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_adana.adana_muzesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/1ERLPa4vJSbyMjRk6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                </div>

            </div>

            @include('user.partial.sidemenu')


        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const faqItem = this.parentElement;
                    const faqAnswer = faqItem.querySelector('.faq-answer');
                    const isActive = this.classList.contains('active');

                    // Tüm açık soruları kapat
                    faqQuestions.forEach(q => {
                        q.classList.remove('active');
                        q.parentElement.querySelector('.faq-answer').classList.remove(
                            'active');
                    });

                    // Eğer tıklanan soru kapalıysa, aç
                    if (!isActive) {
                        this.classList.add('active');
                        faqAnswer.classList.add('active');

                        // Smooth scroll to the opened question
                        setTimeout(() => {
                            faqItem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 100);
                    }
                });
            });

            // Klavye erişilebilirliği
            faqQuestions.forEach(question => {
                question.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        });
    </script>
    {{--    <script> --}}
    {{--        document.addEventListener("DOMContentLoaded", function () { --}}
    {{--            function createTableOfContents() { --}}
    {{--                // Text class'lı div'i bul (dynamicContent yerine) --}}
    {{--                const contentDiv = document.querySelector(".text"); --}}
    {{--                if (!contentDiv) return; --}}

    {{--                // Normal H2'leri bul --}}
    {{--                const normalH2Elements = contentDiv.querySelectorAll('h2:not(.container-faq .header h2)'); --}}

    {{--                // FAQ container'ındaki H2'yi bul --}}
    {{--                const faqH2Elements = contentDiv.querySelectorAll('.container-faq .header h2'); --}}

    {{--                // Tüm H2'leri birleştir (sırasına göre) --}}
    {{--                const allH2Elements = []; --}}

    {{--                // Önce tüm H2'leri pozisyonlarına göre sırala --}}
    {{--                const allH2s = contentDiv.querySelectorAll('h2'); --}}
    {{--                allH2s.forEach(h2 => { --}}
    {{--                    allH2Elements.push(h2); --}}
    {{--                }); --}}

    {{--                if (allH2Elements.length === 0) return; // H2 yoksa işlem yapma --}}

    {{--                // Her H2'ye benzersiz ID ver (eğer yoksa) --}}
    {{--                allH2Elements.forEach((h2, index) => { --}}
    {{--                    if (!h2.id) { --}}
    {{--                        // Başlık metninden ID oluştur (Türkçe karakterleri temizle) --}}
    {{--                        let idText = h2.textContent --}}
    {{--                            .toLowerCase() --}}
    {{--                            .replace(/ğ/g, 'g') --}}
    {{--                            .replace(/ü/g, 'u') --}}
    {{--                            .replace(/ş/g, 's') --}}
    {{--                            .replace(/ı/g, 'i') --}}
    {{--                            .replace(/ö/g, 'o') --}}
    {{--                            .replace(/ç/g, 'c') --}}
    {{--                            .replace(/[^a-z0-9]/g, '-') --}}
    {{--                            .replace(/-+/g, '-') --}}
    {{--                            .replace(/^-|-$/g, ''); --}}

    {{--                        h2.id = idText || `baslik-${index + 1}`; --}}
    {{--                    } --}}
    {{--                }); --}}

    {{--                // İçindekiler listesi oluştur --}}
    {{--                const tocContainer = document.createElement('div'); --}}
    {{--                tocContainer.className = 'table-of-contents'; --}}

    {{--                // FAQ H2'leri için özel metin ve ikon ekleyelim --}}
    {{--                const tocItemsHTML = allH2Elements.map((h2, index) => { --}}
    {{--                    const isFAQ = h2.closest('.container-faq'); --}}
    {{--                    const iconClass = isFAQ ? 'faq-item' : 'normal-item'; --}}
    {{--                    const icon = isFAQ ? '❓' : ''; --}}
    {{--                    const displayText = isFAQ ? 'Sıkça Sorulan Sorular' : h2.textContent; --}}

    {{--                    return ` --}}
    {{--                <li class="${iconClass}"> --}}
    {{--                    <a href="#${h2.id}" class="toc-link"> --}}
    {{--                        ${displayText} ${icon} --}}
    {{--                    </a> --}}
    {{--                </li> --}}
    {{--            `; --}}
    {{--                }).join(''); --}}

    {{--                tocContainer.innerHTML = ` --}}
    {{--            <div class="toc-header"> --}}
    {{--                <h3>Sayfa İçeriği</h3> --}}
    {{--                <button class="toc-toggle" type="button"> --}}
    {{--                    <span class="toc-icon">▼</span> --}}
    {{--                </button> --}}
    {{--            </div> --}}
    {{--            <ul class="toc-list"> --}}
    {{--                ${tocItemsHTML} --}}
    {{--            </ul> --}}
    {{--        `; --}}

    {{--                // İlk H2'nin üstüne içindekiler tablosunu ekle --}}
    {{--                const firstH2 = allH2Elements[0]; --}}
    {{--                firstH2.parentNode.insertBefore(tocContainer, firstH2); --}}

    {{--                // Açık haldeki genişliği hesapla ve sakla --}}
    {{--                function calculateAndSetWidth() { --}}
    {{--                    const tocList = tocContainer.querySelector('.toc-list'); --}}

    {{--                    // Eğer liste gizliyse, geçici olarak görünür yap --}}
    {{--                    const wasHidden = tocList.style.display === 'none'; --}}
    {{--                    if (wasHidden) { --}}
    {{--                        tocList.style.display = 'block'; --}}
    {{--                        tocList.style.visibility = 'hidden'; // Görünmez ama alan kaplar --}}
    {{--                    } --}}

    {{--                    // Gerçek genişliği hesapla --}}
    {{--                    const calculatedWidth = tocContainer.offsetWidth; --}}

    {{--                    // CSS custom property olarak set et --}}
    {{--                    tocContainer.style.setProperty('--toc-calculated-width', calculatedWidth + 'px'); --}}

    {{--                    // Eğer geçici olarak görünür yapılmışsa, eski duruma döndür --}}
    {{--                    if (wasHidden) { --}}
    {{--                        tocList.style.display = 'none'; --}}
    {{--                        tocList.style.visibility = 'visible'; --}}
    {{--                    } --}}
    {{--                } --}}

    {{--                // İlk yüklemede genişliği hesapla --}}
    {{--                setTimeout(calculateAndSetWidth, 50); --}}

    {{--                // Tıklama olaylarını ekle --}}
    {{--                const tocLinks = tocContainer.querySelectorAll('.toc-link'); --}}
    {{--                tocLinks.forEach(link => { --}}
    {{--                    link.addEventListener('click', function (e) { --}}
    {{--                        e.preventDefault(); --}}
    {{--                        const targetId = this.getAttribute('href').substring(1); --}}
    {{--                        const targetElement = document.getElementById(targetId); --}}

    {{--                        if (targetElement) { --}}
    {{--                            // FAQ bölümüne scroll yaparken biraz daha yukarıdan başlat --}}
    {{--                            const isFAQTarget = targetElement.closest('.container-faq'); --}}
    {{--                            const scrollOffset = isFAQTarget ? -30 : 0; --}}

    {{--                            const elementPosition = targetElement.offsetTop + scrollOffset; --}}

    {{--                            window.scrollTo({ --}}
    {{--                                top: elementPosition, --}}
    {{--                                behavior: 'smooth' --}}
    {{--                            }); --}}

    {{--                            // URL'yi güncelle --}}
    {{--                            history.pushState(null, null, `#${targetId}`); --}}
    {{--                        } --}}
    {{--                    }); --}}
    {{--                }); --}}

    {{--                // Toggle butonu işlevselliği --}}
    {{--                const toggleButton = tocContainer.querySelector('.toc-toggle'); --}}
    {{--                const tocList = tocContainer.querySelector('.toc-list'); --}}
    {{--                const tocIcon = tocContainer.querySelector('.toc-icon'); --}}

    {{--                toggleButton.addEventListener('click', function () { --}}
    {{--                    const isOpen = !tocContainer.classList.contains('collapsed'); --}}

    {{--                    if (isOpen) { --}}
    {{--                        // Kapanıyor - önce genişliği hesapla --}}
    {{--                        calculateAndSetWidth(); --}}
    {{--                        tocContainer.classList.add('collapsed'); --}}
    {{--                        tocList.style.display = 'none'; --}}
    {{--                        tocIcon.textContent = '▶'; --}}
    {{--                    } else { --}}
    {{--                        // Açılıyor --}}
    {{--                        tocContainer.classList.remove('collapsed'); --}}
    {{--                        tocList.style.display = 'block'; --}}
    {{--                        tocIcon.textContent = '▼'; --}}
    {{--                        // Açıldıktan sonra genişliği tekrar hesapla --}}
    {{--                        setTimeout(calculateAndSetWidth, 50); --}}
    {{--                    } --}}
    {{--                }); --}}

    {{--                // Pencere boyutu değiştiğinde genişliği yeniden hesapla --}}
    {{--                window.addEventListener('resize', function () { --}}
    {{--                    // Sadece desktop'ta yeniden hesapla (mobil değilse) --}}
    {{--                    if (window.innerWidth > 768) { --}}
    {{--                        setTimeout(calculateAndSetWidth, 100); --}}
    {{--                    } --}}
    {{--                }); --}}
    {{--            } --}}

    {{--            setTimeout(createTableOfContents, 100); --}}
    {{--        }); --}}
    {{--    </script> --}}
@endsection
