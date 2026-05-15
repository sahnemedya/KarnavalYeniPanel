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

                <div class="gorulecek-yerler">

                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/sheraton-grand-adana-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.sheraton_grand_adana_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.sheraton_grand_adana_hotel')</h2>
                        <a href="https://maps.app.goo.gl/mncReq8cbLsywwSY9" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/adana-hiltonsa.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.adana_hiltonsa')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.adana_hiltonsa')</h2>
                        <a href="https://maps.app.goo.gl/F4NsUSDdXN8gMzBz5" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/divan-adana-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.divan_adana_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.divan_adana_hotel')</h2>
                        <a href="https://maps.app.goo.gl/9s716RYGrkWV4nqD8" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/dedeman-otel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.dedeman_otel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.dedeman_otel')</h2>
                        <a href="https://maps.app.goo.gl/TeM51BxWN9wp95P6A" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/doubletree-by-hilton-adana.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.doubletree_by_hilton_adana')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.doubletree_by_hilton_adana')</h2>
                        <a href="https://maps.app.goo.gl/kSMDzaMhzHUuBqK8A" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/otel-seyhan.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.otel_seyhan')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.otel_seyhan')</h2>
                        <a href="https://maps.app.goo.gl/smTSSipjQ5myACvL6" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/ramada-adana-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.ramada_adana_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.ramada_adana_hotel')</h2>
                        <a href="https://maps.app.goo.gl/WjHX75yQZ2pZ88BJA" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/cetinel-tesisleri-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.cetinel_tesisleri_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.cetinel_tesisleri_hotel')</h2>
                        <a href="https://maps.app.goo.gl/PvRHZ6hA1mnAj9Yz6" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>


                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/turkmen-riverside-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.turkmen_riverside_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.turkmen_riverside_hotel')</h2>
                        <a href="https://maps.app.goo.gl/EGh33Kkfn2QrVgbP8" class="yol-tarifi-al" target="_blank">
                            @lang('ortakMetinler.butonlar.yol_tarifi')
                        </a>
                    </div>
                    <div class="yer">
                        <figure>
                            <img src="{{asset('images/user/adana-cevresi/masel-hotel.avif')}}"
                                 alt="@lang('ortakMetinler.adanada_konaklama.masel_hotel')">
                        </figure>
                        <h2 class="baslik">@lang('ortakMetinler.adanada_konaklama.masel_hotel')</h2>
                        <a href="https://maps.app.goo.gl/H2gsQ6VxWiwyr7fJA" class="yol-tarifi-al" target="_blank">
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
