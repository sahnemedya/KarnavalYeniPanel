<section class="breadcrumb">
    <div class="max-width df-center">
        <div class="breadcrumb-list">
            {{-- 'url('/')' helper'ı kullanıldı --}}

            <a href="{{ url('/') }}">@lang('ortakMetinler.anasayfa')</a>

            @php
                $breadcrumbs = null;

                // mainPage varsa onu kullan (fotograf sayfası için)
                $currentPage = isset($mainPage) ? $mainPage : (isset($page) ? $page : null);

                // Önce sayfa varsa onun breadcrumbs'ını al
                if($currentPage && method_exists($currentPage, 'breadcrumbs')) {
                    $breadcrumbs = $currentPage->breadcrumbs();
                }
                // Sayfa yoksa kategori varsa onun breadcrumbs'ını al
                elseif(isset($category) && method_exists($category, 'breadcrumbs')) {
                    $breadcrumbs = $category->breadcrumbs();
                }
            @endphp

            {{-- 1. MEVCUT MANTIĞINIZ (SAYFA VE KATEGORİLER İÇİN) --}}
            @if($breadcrumbs && $breadcrumbs->count() > 0)
                @php
                    $uniqueTitles = [];
                    $filteredBreadcrumbs = collect();

                    // Tekrarları filtrele
                    foreach($breadcrumbs as $breadcrumb) {
                        $title = '';

                        if($breadcrumb instanceof \App\Models\Page) {
                            $title = $breadcrumb->title;
                        } elseif($breadcrumb instanceof \App\Models\Category) {
                            $title = $breadcrumb->name;
                        }

                        if(!in_array($title, $uniqueTitles) && !empty($title)) {
                            $uniqueTitles[] = $title;
                            $filteredBreadcrumbs->push($breadcrumb);
                        }
                    }
                @endphp

                @foreach($filteredBreadcrumbs as $breadcrumb)
                    @php
                        $isPage = $breadcrumb instanceof \App\Models\Page;
                        $isCategory = $breadcrumb instanceof \App\Models\Category;

                        $title = '';
                        $slug = '';

                        if($isPage) {
                            $title = $breadcrumb->title ?? '';
                            $slug = $breadcrumb->slug ?? '';
                        } elseif($isCategory) {
                            $title = $breadcrumb->name ?? '';
                            $slug = $breadcrumb->page ? ($breadcrumb->page->slug ?? '') : '';
                        }
                    @endphp

                    @if(!empty($title))
                        <i class="fa fa-angle-right"></i>

                        @if($loop->last)
                            <span style="font-weight: bold">{{ $title }}</span>
                        @else
                            @if(!empty($slug))
                                {{-- 'url($slug)' helper'ı kullanıldı --}}
                                <a href="{{ url($slug) }}" style="font-weight: bold">{{ $title }}</a>
                            @else
                                <span>{{ $title }}</span>
                            @endif
                        @endif
                    @endif
                @endforeach

                {{-- 2. YENİ EKLEDİĞİMİZ BLOK (DOKTOR DETAY SAYFASI İÇİN) --}}
            @elseif(isset($doktor))
                {{-- Doktor listesi sayfasına link ('Doktor' metniyle) --}}
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('doktorlar') }}" style="font-weight: bold">Tıbbi Kadro</a>

                {{-- Doktorun adı (link yok, sadece metin) --}}
                <i class="fa fa-angle-right"></i>
                <span style="font-weight: bold">{{ $doktor->title }}</span>

            @endif
            {{-- @if bloğu burada biter --}}

        </div>
    </div>
</section>
