<nav class="topbar koyu-arkaplan">
    <a href="{{route("cms.dashboard")}}" class="logo">
        <img src="{{asset("images/panel/site/logo.png")}}" alt="">
    </a>
    <div class="user">
        <figure>
            <img src="{{asset("images/panel/site/logo.jpg")}}" alt="">
        </figure>
        <div class="username">{{ Auth::user()->name }}<i class="las la-angle-down"></i></div>
        <ul class="alt-menu">
            <li><a href="{{route('cms.profile.edit')}}">Profil</a></li>
            <li>
                <form action="{{ route('cms.logout') }}" method="POST" class="logout-form">
                    @csrf
                    <input type="submit" value="Çıkış Yap">
                </form>
            </li>
        </ul>
    </div>
</nav>


<nav class="sidebar koyu-arkaplan">
    <ul class="ust-menu">
        <li class="ust-menu-li {{ request()->routeIs('cms.dashboard') ? 'aktif' : '' }}">
            <a href="{{route('cms.dashboard')}}">Anasayfa </a>
        </li>
        @permission('blade')
        {{--        Blade --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.blades.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.blades.index')}}">Blade <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.blades.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.blades.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.blades.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.blades.create')}}">Oluştur</a>
                </li>

            </ul>
        </li>
        @endpermission

        @permission('dil')
        {{--        Language --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.languages.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.languages.index')}}">Dil <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.languages.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.languages.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.languages.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.languages.create')}}">Oluştur</a>
                </li>

            </ul>
        </li>
        @endpermission
        @permission('karnaval-sezonu')
        {{--                Sezon--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.karnaval-sezonu.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.karnaval-sezonu.index')}}">Karnaval Sezonu <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.karnaval-sezonu.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.karnaval-sezonu.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.karnaval-sezonu.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.karnaval-sezonu.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.karnaval-sezonu.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.karnaval-sezonu.deleted')}}">Silinenler</a>
                </li>

            </ul>
        </li>
        @endpermission
        @permission('kategori')
        {{--        Category --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.category.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.category.index')}}">Kategoriler <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.category.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.category.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.category.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.category.create')}}">Oluştur</a>
                </li>

                <li class="{{ request()->routeIs('cms.category.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.category.deleted')}}">Silinenler</a>
                </li>

            </ul>
        </li>
        @endpermission

        @permission('sayfa')
        {{--        Pages --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.pages.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.pages.index')}}">Sayfalar <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.pages.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.pages.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.pages.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.pages.create')}}">Oluştur</a>
                </li>

                <li class="{{ request()->routeIs('cms.pages.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.pages.deleted')}}">Silinenler</a>
                </li>

            </ul>
        </li>
        @endpermission

{{--        @permission('doktorlar')--}}
{{--        <li class="ust-menu-li {{ request()->routeIs('cms.doctors.*') ? 'aktif' : '' }}">--}}
{{--            <a href="{{route('cms.doctors.index')}}">Doktorlar <i class="las la-angle-right"></i></a>--}}

{{--            <ul class="alt-menu">--}}

{{--                <li class="{{ request()->routeIs('cms.doctors.index') ? 'aktif' : '' }}">--}}
{{--                    <a href="{{route('cms.doctors.index')}}">Tümü</a>--}}
{{--                </li>--}}

{{--                <li class="{{ request()->routeIs('cms.doctors.create') ? 'aktif' : '' }}">--}}
{{--                    <a href="{{route('cms.doctors.create')}}">Oluştur</a>--}}
{{--                </li>--}}

{{--                <li class="{{ request()->routeIs('cms.doctors.deleted') ? 'aktif' : '' }}">--}}
{{--                    <a href="{{route('cms.doctors.deleted')}}">Silinenler</a>--}}
{{--                </li>--}}

{{--            </ul>--}}
{{--        </li>--}}
{{--        @endpermission--}}

        @permission('sss')
        {{-- Sıkça Sorulan Sorular --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.faqs.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.faqs.index')}}">SSS <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                {{-- 1. LİSTELEME (TR - Varsayılan) --}}
                <li class="{{ request()->routeIs('cms.faqs.index') && !request('lang_id') ? 'aktif' : '' }}">
                    <a href="{{route('cms.faqs.index')}}">Listele (TR)</a>
                </li>

                {{-- 2. LİSTELEME (DİĞER DİLLER) --}}
                @if(isset($languages))
                    @foreach($languages as $lang)
                        @if($lang->id == 1) @continue @endif
                        <li class="{{ request()->routeIs('cms.faqs.index') && request('lang_id') == $lang->id ? 'aktif' : '' }}">
                            <a href="{{ route('cms.faqs.index', ['lang_id' => $lang->id]) }}">
                                Listele ({{ strtoupper($lang->code) }})
                            </a>
                        </li>
                    @endforeach
                @endif

                <li class="{{ request()->routeIs('cms.faqs.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.faqs.create')}}">Oluştur</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('kurumsal-kimlik')
        {{--        Certificates --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.corporateIdentity.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.corporateIdentity.index')}}">Kurumsal Kimlik <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.corporateIdentity.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.corporateIdentity.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.corporateIdentity.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.corporateIdentity.create')}}">Oluştur</a>
                </li>
            </ul>

        </li>
        @endpermission


        @permission('ozel-menuler')
        @foreach($specialMenus as $menu)
            @php
                // Mevcut URL'deki ID ve Dil parametrelerini alıyoruz
                $currentId = request()->route('id') ?? request()->route('categoryId');
                $currentLangId = request('lang_id');

                // Bu menü açık mı kontrolü (ID eşleşiyor mu ve side-menu rotasında mıyız?)
                $isActive = $currentId == $menu->id && request()->routeIs('cms.side-menu-elements.*');
            @endphp

            <li class="ust-menu-li {{ $isActive ? 'aktif' : '' }}">
                {{-- Menü Başlığı --}}
                @if($menu->name == "Koruyucu Sağlık Ve Sağlığın Geliştirilmesine Yönelik Etkinlikler - Blog")
                    <a href="#">Blog <i class="las la-angle-right"></i></a>
                @else
                    <a href="#">{{$menu->name}} <i class="las la-angle-right"></i></a>
                @endif

                {{-- Alt Menü Linkleri --}}
                <ul class="alt-menu">

                    {{-- 1. LİSTELEME (TÜRKÇE - Varsayılan) --}}
                    <li class="{{ request()->routeIs('cms.side-menu-elements.index') && $isActive && !$currentLangId ? 'aktif' : '' }}">
                        <a href="{{route('cms.side-menu-elements.index',$menu->id)}}">Listele (TR)</a>
                    </li>

                    {{-- 2. LİSTELEME (DİĞER DİLLER) --}}
                    @if(isset($languages))
                        @foreach($languages as $lang)
                            @if($lang->id == 1) @continue @endif {{-- Türkçe'yi atla --}}

                            <li class="{{ request()->routeIs('cms.side-menu-elements.index') && $isActive && $currentLangId == $lang->id ? 'aktif' : '' }}">
                                <a href="{{ route('cms.side-menu-elements.index', ['id' => $menu->id, 'lang_id' => $lang->id]) }}">
                                    Listele ({{ strtoupper($lang->code) }})
                                </a>
                            </li>
                        @endforeach
                    @endif

                    {{-- 3. EKLEME --}}
                    <li class="{{ request()->routeIs('cms.side-menu-elements.create') && $isActive ? 'aktif' : '' }}">
                        <a href="{{route('cms.side-menu-elements.create',$menu->id)}}">Ekle</a>
                    </li>

                    {{-- 4. SİLİNENLER (TÜRKÇE - Varsayılan) --}}
                    <li class="{{ request()->routeIs('cms.side-menu-elements.deleted') && $isActive && !$currentLangId ? 'aktif' : '' }}">
                        <a href="{{route('cms.side-menu-elements.deleted',$menu->id)}}">Silinenler (TR)</a>
                    </li>

                    {{-- 5. SİLİNENLER (DİĞER DİLLER) --}}
                    @if(isset($languages))
                        @foreach($languages as $lang)
                            @if($lang->id == 1) @continue @endif {{-- Türkçe'yi atla --}}

                            <li class="{{ request()->routeIs('cms.side-menu-elements.deleted') && $isActive && $currentLangId == $lang->id ? 'aktif' : '' }}">
                                <a href="{{ route('cms.side-menu-elements.deleted', ['categoryId' => $menu->id, 'lang_id' => $lang->id]) }}">
                                    Silinenler ({{ strtoupper($lang->code) }})
                                </a>
                            </li>
                        @endforeach
                    @endif

                </ul>
            </li>
        @endforeach
        @endpermission



        @permission('seo')
        {{--        Seo --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.seos.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.seos.index')}}">Seo & Geo <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.seos.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.seos.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.seos.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.seos.create')}}">Oluştur</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('ozellikler')
        {{--        features --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.features.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.features.index')}}">Özellikler <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.features.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.features.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.features.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.features.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.features.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.features.deleted')}}">Silinenler</a>
                </li>
            </ul>

        </li>
        @endpermission

        @permission('slider')
        {{--        Slider --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.slider.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.slider.index')}}">Slayt - Banner <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.slider.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.slider.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.slider.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.slider.create')}}">Oluştur</a>
                </li>

            </ul>
        </li>
        @endpermission

        @permission('galeri')
        {{--        Slider --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.gallery.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.gallery.index')}}">Galeri <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.gallery.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.gallery.index')}}">Tümü</a>
                </li>

            </ul>
        </li>
        @endpermission
        @permission('referans-turu')
        {{--        References --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.reference-types.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.reference-types.index')}}">Referanslar Türü<i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.reference-types.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.reference-types.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.reference-types.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.reference-types.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.reference-types.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.reference-types.deleted')}}">Silinenler</a>
                </li>
            </ul>

        </li>
        @endpermission
        @permission('referanslar')
        {{--        References --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.references.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.references.index')}}">Referanslar <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.references.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.references.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.references.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.references.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.references.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.references.deleted')}}">Silinenler</a>
                </li>
            </ul>

        </li>
        @endpermission

        @permission('sertifikalar')
        {{--        Certificates --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.certificate.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.certificate.index')}}">Sertifikalar <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.certificate.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.certificate.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.certificate.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.certificate.create')}}">Oluştur</a>
                </li>
            </ul>

        </li>
        @endpermission
        @permission('formlar')
        {{--        Formlar --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.forms.*') ? 'aktif' : '' }}">
            <a href="">Formlar <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.forms.iletisimFormu') ? 'aktif' : '' }}">
                    <a href="{{ route('cms.forms.iletisimFormu') }}"> Gelen Mailler</a>
                </li>


                <li class="{{ request()->routeIs('cms.forms.insanKaynaklariFormu') ? 'aktif' : '' }}">
                    <a href="{{ route('cms.forms.insanKaynaklariFormu') }}">İnsan Kaynakları Formları</a>
                </li>


                <li class="{{ request()->routeIs('cms.forms.balkonVitrinBasvurulari') ? 'aktif' : '' }}">
                    <a href="{{ route('cms.forms.balkonVitrinBasvurulari') }}">Balkon V. Y. Gelen Mailler</a>
                </li>


                <li class="{{ request()->routeIs('cms.forms.bultenFormu') ? 'aktif' : '' }}">
                    <a href="{{ route('cms.forms.bultenFormu') }}">Bülten Aboneliği Gelen Mailler</a>
                </li>


                <li class="{{ request()->routeIs('cms.forms.portakalliLezzetler') ? 'aktif' : '' }}">
                    <a href="{{ route('cms.forms.portakalliLezzetler') }}">Portakal Lezzetler Gelen Mailler</a>
                </li>






            </ul>
        </li>
        @endpermission

        @permission('basin-kiti')
        {{--        Preess Kits --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.press-kit.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.press-kit.index')}}">Basın Kiti <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.press-kit.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.press-kit.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.press-kit.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.press-kit.create')}}">Oluştur</a>
                </li>
            </ul>

        </li>
        @endpermission
        @permission('yorumlar')
        <li class="ust-menu-li {{ request()->routeIs('cms.comments.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.comments.index')}}">Yorumcular <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.comments.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.comments.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.comments.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.comments.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.comments.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.comments.deleted')}}">Silinenler</a>
                </li>
            </ul>

        </li>
        @endpermission

 @permission('popup')
        {{--        Popup --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.popup.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.popup.index')}}">Pop-Up <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.popup.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.popup.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.popup.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.popup.create')}}">Oluştur</a>
                </li>
            </ul>
        </li>
        @endpermission



        @permission('iletisim')
        {{--        Contacts --}}
        <li class="ust-menu-li {{ request()->routeIs('cms.contacts.*') ? 'aktif' : '' }}">
            <a href="{{route('cms.contacts.index')}}">İletişim Bilgileri <i class="las la-angle-right"></i></a>

            <ul class="alt-menu">

                <li class="{{ request()->routeIs('cms.contacts.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.contacts.index')}}">Tümü</a>
                </li>

                <li class="{{ request()->routeIs('cms.seos.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.contacts.create')}}">Oluştur</a>
                </li>
                <li class="{{ request()->routeIs('cms.seos.deleted') ? 'aktif' : '' }}">
                    <a href="{{route('cms.contacts.deleted')}}">Silinenler</a>
                </li>
            </ul>

        </li>
        @endpermission


        @permission('kullanıcılar')
        {{--        Users--}}
        <li class="ust-menu-li {{   request()->routeIs('cms.register') ||  request()->routeIs('cms.users.*')  ? 'aktif' : '' }}">
            <a href="#">Kullanıcılar <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.users.index') || request()->routeIs('cms.register') ? 'aktif' : '' }}">
                    <a href="{{route('cms.users.index')}}">Tümü</a>
                </li>
                <li class="{{ request()->routeIs('cms.register') ? 'aktif' : '' }}">
                    <a href="{{route('cms.register')}}">Kullanıcı Ekle</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('roller')
        {{--        Roles--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.roles.*') ? 'aktif' : '' }}">
            <a href="#">Roller <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.roles.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.roles.index')}}">Rol Listesi</a>
                </li>
                <li class="{{ request()->routeIs('cms.roles.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.roles.create')}}">Rol Ekle</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('yetkiler')
        {{--        User Roles Setting--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.role-user.*') ? 'aktif' : '' }}">
            <a href="#">Yetkiler <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.role-user.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.role-user.index')}}">Yetki Listesi</a>
                </li>
                <li class="{{ request()->routeIs('cms.role-user.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.role-user.create')}}">Yetki Ata</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('izinler')
        {{--        Permission Setting--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.permission.*') ? 'aktif' : '' }}">
            <a href="#">İzinler <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.permission.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.permission.index')}}">İzin Listesi</a>
                </li>
                <li class="{{ request()->routeIs('cms.permission.create') ? 'aktif' : '' }}">
                    <a href="{{route('cms.permission.create')}}">İzin Ekle</a>
                </li>
            </ul>
        </li>
        @endpermission

        @permission('yetki-izinleri')
        {{--        Role Permission Setting--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.role-permission.*') ? 'aktif' : '' }}">
            <a href="#">Yetki İzinleri <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.role-permission.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.role-permission.index')}}">Yetki İzin Listesi</a>
                </li>
            </ul>
        </li>
        @endpermission
        @permission('ayarlar')
        {{--        Ayarlar--}}
        <li class="ust-menu-li {{ request()->routeIs('cms.settings.*') ? 'aktif' : '' }}">
            <a href="#">Ayarlar <i class="las la-angle-right"></i></a>
            <ul class="alt-menu">
                <li class="{{ request()->routeIs('cms.settings.site.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.settings.site.index')}}">Site Ayarları</a>
                </li>
                <li class="{{ request()->routeIs('cms.settings.api-key.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.settings.api-key.index')}}">API Key Ayarları</a>
                </li>
                <li class="{{ request()->routeIs('cms.settings.panel-menu.index') ? 'aktif' : '' }}">
                    <a href="{{route('cms.settings.panel-menu.index')}}">Panel Menu Ayarları</a>
                </li>
            </ul>
        </li>
        @endpermission



    </ul>
</nav>
