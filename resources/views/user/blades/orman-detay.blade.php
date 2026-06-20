@extends('user.partial.master')
@section('content')
    <style>
        /* Pagination Kapsayıcısı */
        .custom-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;           /* Butonlar arasındaki boşluk */
            margin-top: 50px;   /* Resimlerden uzaklaştıran üst boşluk */
            margin-bottom: 30px;
        }

        /* Standart Buton Görünümü */
        .sayfalama-btn {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px; /* Modern yuvarlak hatlar */
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); /* Hafif derinlik */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Hover (Üzerine Gelince) Efekti */
        .sayfalama-btn:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-2px); /* Tıklanma hissi için hafif yukarı kalkma */
        }

        /* Aktif Sayfa Butonu Görünümü */
        .sayfalama-btn.active {
            background-color: #f97316; /* Etkinliğe uygun turuncu tonu */
            border-color: #f97316;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3); /* Turuncu ışıma/gölge */
            transform: translateY(-2px);
        }
    </style>
    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>


                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif


                @if($page->gallery->count()>0)

                    <div class="galeri">
                        @foreach($page->gallery as $galeri)
                            <figure class="galeri-figure gallery-item" data-fancybox="gallery"
                                    data-src="{{$galeri->image()}}"
                                    data-caption="{{$galeri->baslik}} - {{$loop->index+1}}">
                                <img src="{{$galeri->image()}}"
                                     alt="{{$galeri->name}} Galeri Resmi {{$loop->index}}">
                            </figure>
                        @endforeach
                    </div>

                    <div class="custom-pagination" id="paginationControls"></div>
                @endif


            </div>
            @include("user.partial.sidemenu")


        </div>
    </section>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tüm resimleri seç
        const items = document.querySelectorAll('.gallery-item');
        const paginationControls = document.getElementById('paginationControls');

        // Ayarlar
        const itemsPerPage = 18; // Bir sayfada gösterilecek resim sayısı (15 yapmak istersen burayı değiştir)
        let currentPage = 1;

        // Eğer hiç resim yoksa scripti durdur
        if (items.length === 0) return;

        // İlgili sayfadaki resimleri gösteren fonksiyon
        function showPage(page) {
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Tüm resimleri dön ve sadece bu sayfaya ait olanları göster
            items.forEach((item, index) => {
                if (index >= startIndex && index < endIndex) {
                    item.style.display = 'block'; // Göster
                } else {
                    item.style.display = 'none'; // Gizle
                }
            });

            updateButtons(page);
        }

        // Sayfalama butonlarını (1, 2, 3...) oluşturan fonksiyon
        function setupPagination() {
            const totalPages = Math.ceil(items.length / itemsPerPage);
            paginationControls.innerHTML = ''; // İçini temizle

            // Tek sayfa varsa buton oluşturmaya gerek yok
            if (totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.classList.add('btn', 'btn-outline-primary', 'sayfalama-btn'); // Bootstrap class'ları
                btn.innerText = i;

                // Butona tıklanınca o sayfayı göster
                btn.addEventListener('click', function () {
                    currentPage = i;
                    showPage(currentPage);
                });

                paginationControls.appendChild(btn);
            }
        }

        // Aktif olan butonu renklendiren fonksiyon
        function updateButtons(page) {
            const buttons = paginationControls.querySelectorAll('button');
            buttons.forEach((btn, index) => {
                if (index + 1 === page) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary'); // Aktif buton rengi
                } else {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary'); // Pasif buton rengi
                }
            });
        }

        // Sayfa yüklendiğinde sistemi başlat ve 1. sayfayı göster
        setupPagination();
        showPage(1);
    });
</script>





