@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>

                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif

                <div class="gezilecek-yerler-ic">
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ulu-camii.jpg') }}" alt="Ulu Camii">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.ulu_camii_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.ulu_camii_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/d87Y1iijbMWBeiME8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tarsus-taskuyu-magarasi.jpg') }}" alt="Tarsus Taşkuyu Mağarası">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.taskuyu_magarasi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.taskuyu_magarasi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/XT82uJv4uvUJq7xo9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tarsus-selalesi.jpg') }}" alt="Tarsus Şelalesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_selalesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_selalesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/mZqiuQBrWU7wHHSt7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tarsus-muzesi.jpg') }}" alt="Tarsus Müzesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_muzesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_muzesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/jtHujXaqJc6L9XJbA" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/tarihi-tarsus-evleri.jpg') }}" alt="Tarihi Tarsus Evleri">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_evleri_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.tarsus_evleri_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/8nCRFmzF8VCYYPsQ6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/saint-paul-kuyusu.jpg') }}" alt="Saint Paul Kuyusu">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.saint_paul_kuyusu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.saint_paul_kuyusu_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/Akq3UGRoSztNant19" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/saint-paul-kilisesi.jpg') }}" alt="Saint Paul Kilisesi">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.saint_paul_kilisesi_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.saint_paul_kilisesi_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/uzBguWRvxMnUraX4A" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/roma-yolu-saglikli-koyu.jpg') }}" alt="Roma Yolu Sağlıklı Köyü">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_yolu_saglikli_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_yolu_saglikli_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/v6x8ozMtyV6DVSDf8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/roma-yolu.jpg') }}" alt="Roma Yolu">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_yolu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_yolu_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/ycz9WLC2Uxstq5af9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/roma-hamami.jpg') }}" alt="Roma Hamamı">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_hamami_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.roma_hamami_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/w1JY5aXd2dcwzbpq7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/nusret-mayin-gemisi.jpg') }}"
                                alt="Nusret Mayın Gemisi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.nusret_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.nusret_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/P68GzZEgCwF55isU7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/kubatpasa-medresesi.jpg') }}"
                                alt="Kubatpaşa Medresesi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.kubatpasa_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.kubatpasa_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/fGzrcedN66g37XhR7" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/kleopatra.jpg') }}" alt="Kleopatra">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.kleopatra_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.kleopatra_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/odLoNPwFAnNYzFXG6" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/kirkkasik-bedesteni.jpg') }}" alt="Kırkkaşık Bedesteni">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.kirkkasik_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.kirkkasik_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/6fYUryY5TdKWhgwm8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/eyuboglu-konagi.jpg') }}" alt="Eyüboğlu Konağı">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.eyuboglu_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.eyuboglu_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/YQo3FQoc86CN8caf8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/eski-camii.jpg') }}" alt="Eski Camii">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.eski_camii_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.eski_camii_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/ojtMbT6wen9GAaKn8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/donuktas-roma-tapinagi.jpg') }}"
                                alt="Donuktaş Roma Tapınağı">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.donuktas_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.donuktas_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/eXYUgt6o4E3RuHWJ9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/danyal-peygamber-makami.jpg') }}"
                                alt="Danyal Peygamber Makamı">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.danyal_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.danyal_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/6ZgLmcDrpvKW22Nd9" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/bilal-i-habesi.jpg') }}"
                                alt="Bilal-i Habeşi">
                        </figure>
                        <div class="aciklama">
                            <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.bilal_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.bilal_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/EoRenavFAcFMWYhYA" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{ asset('images/user/adana-cevresi/ashab-i-kehf-ve-yedi-uyurlar.jpg') }}"
                                alt="Ashab-ı Kehf ve Yedi Uyurlar">
                        </figure>
                        <div class="aciklama">
                           <h2 class="baslik">@lang('ortakMetinler.gezilecek_yerler_tarsus.ashab_i_kehf_baslik')</h2>
                            <p>@lang('ortakMetinler.gezilecek_yerler_tarsus.ashab_i_kehf_aciklama')</p>
                        </div>
                        <a href="https://maps.app.goo.gl/eN8FB7W7mX2EgmwY8" class="yol-tarifi-al">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                </div>

            </div>

            @include('user.partial.sidemenu')


        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(question => {
                question.addEventListener('click', function () {
                    const faqItem = this.parentElement;
                    const faqAnswer = faqItem.querySelector('.faq-answer');
                    const isActive = this.classList.contains('active');

                    // Tüm açık soruları kapat
                    faqQuestions.forEach(q => {
                        q.classList.remove('active');
                        q.parentElement.querySelector('.faq-answer').classList.remove('active');
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
                question.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        });
    </script>
{{--    <script>--}}
{{--        document.addEventListener("DOMContentLoaded", function () {--}}
{{--            function createTableOfContents() {--}}
{{--                // Text class'lı div'i bul (dynamicContent yerine)--}}
{{--                const contentDiv = document.querySelector(".text");--}}
{{--                if (!contentDiv) return;--}}

{{--                // Normal H2'leri bul--}}
{{--                const normalH2Elements = contentDiv.querySelectorAll('h2:not(.container-faq .header h2)');--}}

{{--                // FAQ container'ındaki H2'yi bul--}}
{{--                const faqH2Elements = contentDiv.querySelectorAll('.container-faq .header h2');--}}

{{--                // Tüm H2'leri birleştir (sırasına göre)--}}
{{--                const allH2Elements = [];--}}

{{--                // Önce tüm H2'leri pozisyonlarına göre sırala--}}
{{--                const allH2s = contentDiv.querySelectorAll('h2');--}}
{{--                allH2s.forEach(h2 => {--}}
{{--                    allH2Elements.push(h2);--}}
{{--                });--}}

{{--                if (allH2Elements.length === 0) return; // H2 yoksa işlem yapma--}}

{{--                // Her H2'ye benzersiz ID ver (eğer yoksa)--}}
{{--                allH2Elements.forEach((h2, index) => {--}}
{{--                    if (!h2.id) {--}}
{{--                        // Başlık metninden ID oluştur (Türkçe karakterleri temizle)--}}
{{--                        let idText = h2.textContent--}}
{{--                            .toLowerCase()--}}
{{--                            .replace(/ğ/g, 'g')--}}
{{--                            .replace(/ü/g, 'u')--}}
{{--                            .replace(/ş/g, 's')--}}
{{--                            .replace(/ı/g, 'i')--}}
{{--                            .replace(/ö/g, 'o')--}}
{{--                            .replace(/ç/g, 'c')--}}
{{--                            .replace(/[^a-z0-9]/g, '-')--}}
{{--                            .replace(/-+/g, '-')--}}
{{--                            .replace(/^-|-$/g, '');--}}

{{--                        h2.id = idText || `baslik-${index + 1}`;--}}
{{--                    }--}}
{{--                });--}}

{{--                // İçindekiler listesi oluştur--}}
{{--                const tocContainer = document.createElement('div');--}}
{{--                tocContainer.className = 'table-of-contents';--}}

{{--                // FAQ H2'leri için özel metin ve ikon ekleyelim--}}
{{--                const tocItemsHTML = allH2Elements.map((h2, index) => {--}}
{{--                    const isFAQ = h2.closest('.container-faq');--}}
{{--                    const iconClass = isFAQ ? 'faq-item' : 'normal-item';--}}
{{--                    const icon = isFAQ ? '❓' : '';--}}
{{--                    const displayText = isFAQ ? 'Sıkça Sorulan Sorular' : h2.textContent;--}}

{{--                    return `--}}
{{--                <li class="${iconClass}">--}}
{{--                    <a href="#${h2.id}" class="toc-link">--}}
{{--                        ${displayText} ${icon}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            `;--}}
{{--                }).join('');--}}

{{--                tocContainer.innerHTML = `--}}
{{--            <div class="toc-header">--}}
{{--                <h3>Sayfa İçeriği</h3>--}}
{{--                <button class="toc-toggle" type="button">--}}
{{--                    <span class="toc-icon">▼</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <ul class="toc-list">--}}
{{--                ${tocItemsHTML}--}}
{{--            </ul>--}}
{{--        `;--}}

{{--                // İlk H2'nin üstüne içindekiler tablosunu ekle--}}
{{--                const firstH2 = allH2Elements[0];--}}
{{--                firstH2.parentNode.insertBefore(tocContainer, firstH2);--}}

{{--                // Açık haldeki genişliği hesapla ve sakla--}}
{{--                function calculateAndSetWidth() {--}}
{{--                    const tocList = tocContainer.querySelector('.toc-list');--}}

{{--                    // Eğer liste gizliyse, geçici olarak görünür yap--}}
{{--                    const wasHidden = tocList.style.display === 'none';--}}
{{--                    if (wasHidden) {--}}
{{--                        tocList.style.display = 'block';--}}
{{--                        tocList.style.visibility = 'hidden'; // Görünmez ama alan kaplar--}}
{{--                    }--}}

{{--                    // Gerçek genişliği hesapla--}}
{{--                    const calculatedWidth = tocContainer.offsetWidth;--}}

{{--                    // CSS custom property olarak set et--}}
{{--                    tocContainer.style.setProperty('--toc-calculated-width', calculatedWidth + 'px');--}}

{{--                    // Eğer geçici olarak görünür yapılmışsa, eski duruma döndür--}}
{{--                    if (wasHidden) {--}}
{{--                        tocList.style.display = 'none';--}}
{{--                        tocList.style.visibility = 'visible';--}}
{{--                    }--}}
{{--                }--}}

{{--                // İlk yüklemede genişliği hesapla--}}
{{--                setTimeout(calculateAndSetWidth, 50);--}}

{{--                // Tıklama olaylarını ekle--}}
{{--                const tocLinks = tocContainer.querySelectorAll('.toc-link');--}}
{{--                tocLinks.forEach(link => {--}}
{{--                    link.addEventListener('click', function (e) {--}}
{{--                        e.preventDefault();--}}
{{--                        const targetId = this.getAttribute('href').substring(1);--}}
{{--                        const targetElement = document.getElementById(targetId);--}}

{{--                        if (targetElement) {--}}
{{--                            // FAQ bölümüne scroll yaparken biraz daha yukarıdan başlat--}}
{{--                            const isFAQTarget = targetElement.closest('.container-faq');--}}
{{--                            const scrollOffset = isFAQTarget ? -30 : 0;--}}

{{--                            const elementPosition = targetElement.offsetTop + scrollOffset;--}}

{{--                            window.scrollTo({--}}
{{--                                top: elementPosition,--}}
{{--                                behavior: 'smooth'--}}
{{--                            });--}}

{{--                            // URL'yi güncelle--}}
{{--                            history.pushState(null, null, `#${targetId}`);--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}

{{--                // Toggle butonu işlevselliği--}}
{{--                const toggleButton = tocContainer.querySelector('.toc-toggle');--}}
{{--                const tocList = tocContainer.querySelector('.toc-list');--}}
{{--                const tocIcon = tocContainer.querySelector('.toc-icon');--}}

{{--                toggleButton.addEventListener('click', function () {--}}
{{--                    const isOpen = !tocContainer.classList.contains('collapsed');--}}

{{--                    if (isOpen) {--}}
{{--                        // Kapanıyor - önce genişliği hesapla--}}
{{--                        calculateAndSetWidth();--}}
{{--                        tocContainer.classList.add('collapsed');--}}
{{--                        tocList.style.display = 'none';--}}
{{--                        tocIcon.textContent = '▶';--}}
{{--                    } else {--}}
{{--                        // Açılıyor--}}
{{--                        tocContainer.classList.remove('collapsed');--}}
{{--                        tocList.style.display = 'block';--}}
{{--                        tocIcon.textContent = '▼';--}}
{{--                        // Açıldıktan sonra genişliği tekrar hesapla--}}
{{--                        setTimeout(calculateAndSetWidth, 50);--}}
{{--                    }--}}
{{--                });--}}

{{--                // Pencere boyutu değiştiğinde genişliği yeniden hesapla--}}
{{--                window.addEventListener('resize', function () {--}}
{{--                    // Sadece desktop'ta yeniden hesapla (mobil değilse)--}}
{{--                    if (window.innerWidth > 768) {--}}
{{--                        setTimeout(calculateAndSetWidth, 100);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}

{{--            setTimeout(createTableOfContents, 100);--}}
{{--        });--}}
{{--    </script>--}}
@endsection











