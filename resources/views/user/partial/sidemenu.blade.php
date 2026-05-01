<div class="sidemenu">
    @if($navbarMenus->count() > 0)
        @foreach($navbarMenus as $category)
            @php
                // Kategori için ana sayfa var mı kontrol et
                $mainPage = null;
                if($category->page) {
                    $mainPage = $category->page;
                } else {
                    // Kategori name ile aynı title'a sahip sayfa var mı?
                    $mainPage = $category->subPages->firstWhere('title', $category->name);
                }

                // Parent page kontrolü
                $isChildPage = ($currentPage && $currentPage->parent_page);
                $parentPage = null;

                if($isChildPage) {
                    $parentPage = \App\Models\Page::find($currentPage->parent_page);
                }
            @endphp

            @if($isChildPage && $parentPage)

                @if($parentPage->slug == 'online-islemler')

                    <a href="{{ url('randevu-al') }}" class="@if($currentSlug == 'online-randevu') active @endif">
                        Online Randevu <i class="fa fa-chevron-right right-i"></i>
                    </a>

                    <a href="{{ url('doktorunuza-sorun') }}" class="@if($currentSlug == 'doktorunuza-sorun') active @endif">
                        Doktorunuza Sorun <i class="fa fa-chevron-right right-i"></i>
                    </a>

                    <a href="https://medisoftweb.com/hastane-online-islemler/ozel-ortadogu-hastanesi" target="_blank">E-Sonuç <i class="fa fa-chevron-right right-i"></i></a>

                    <a href="{{ url('online-muayene') }}" class="@if($currentSlug == 'online-muayene') active @endif">
                        Online Muayene <i class="fa fa-chevron-right right-i"></i>
                    </a>

                    <a href="{{ url('memnuniyet-anketi') }}" class="@if($currentSlug == 'memnuniyet-anketi') active @endif">
                        Memnuniyet Anketi <i class="fa fa-chevron-right right-i"></i>
                    </a>

                @else

                    @php
                        $siblingPages = \App\Models\Page::where('parent_page', $parentPage->id)
                            ->where('published', 1)
                            ->orderBy('id', 'asc')
                            ->get();
                    @endphp

                    @foreach($siblingPages as $siblingPage)
                        <a href="{{ url($siblingPage->slug) }}"
                           class="@if($siblingPage->slug == $currentSlug) active @endif">
                            <img src="{{asset("images/user/gecici/clean.png")}}" alt="">
                            {{ $siblingPage->title }}
{{--                            <i class="fa fa-chevron-right right-i"></i>--}}
                        </a>
                    @endforeach
                @endif
            @else
                @php
                    $subPagesList = $category->subPages->filter(function($page) use ($mainPage) {
                        // Parent'ı olmayanlar
                        if($page->parent_page) return false;

                        // Ana sayfa değilse
                        if($mainPage && $page->id == $mainPage->id) return false;

                        return true;
                    });
                @endphp

                @foreach($subPagesList as $subPage)
                    <a href="{{ url($subPage->slug) }}"
                       class="@if($subPage->slug == $currentSlug) active @endif">
                        <img src="{{asset("images/user/gecici/clean.png")}}" alt="">
                        {{ $subPage->title }}
{{--                        <i class="fa fa-chevron-right right-i"></i>--}}
                    </a>
                @endforeach
            @endif
        @endforeach
    @endif
</div>
