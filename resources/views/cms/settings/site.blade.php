@extends("cms.partial.layout")
@section("content")
    <div class="row">
        <div class="card col-sm-12 col-md-12 col-lg-6">
            <div class="card-header">Site Ayarları</div>
            <div class="card-body">
                <form action="{{route("cms.settings.site.update",1)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")

                    <label for="siteName">Site Adı:</label>
                    <input type="text" name="site_name" id="siteName" placeholder="Site Adı"
                           value="{{ $siteSettings->site_name }}">
                    <label for="seo_title">Site Title:</label>
                    <input type="text" name="seo_title" id="seo_title" placeholder="Site Title"
                           value="{{ $siteSettings->seo_title }}">
                    <label for="siteName">Site Description:</label>
                    <input type="text" name="description" id="description" placeholder="Site Description"
                           value="{{ $siteSettings->description }}">


                    <label for="logo">Logo:</label>
                    <div class="display-flex justify-between">

                        <input type="file" name="logo" id="logo" title="Site Logosunu Seçin" class="col-sm-10">

                        <img
                            @if($siteSettings->logo)
                                src="{{asset("images/site/".$siteSettings->logo )}}"
                            @else
                                src="{{asset("images/site/nophoto.png")}}"
                            @endif

                            height="40" width="40" data-fancybox="Site Ayarları" data-caption="Site Logo"
                            alt="">
                    </div>

                    <label for="favicon">Favicon:</label>
                    <div class="display-flex justify-between">
                        <input type="file" name="favicon" id="favicon" title="Site Favicon Dosyasını Seçin"
                               class="col-sm-10">

                        <img
                            @if($siteSettings->favicon)
                                src="{{asset("images/site/".$siteSettings->favicon )}}"
                            @else
                                src="{{asset("images/site/nophoto.png")}}"
                            @endif

                            height="40" width="40" data-fancybox="Site Ayarları" data-caption="Favicon" alt>
                    </div>

                    <label for="footerLogo">Footer Logo:</label>
                    <div class="display-flex justify-between">
                        <input type="file" name="footer_logo" id="footerLogo" title="Footer Logosunu Seçin"
                               class="col-sm-10">

                        <img
                            @if($siteSettings->footer_logo)
                                src="{{asset("images/site/".$siteSettings->footer_logo)}}"
                            @else
                                src="{{asset("images/site/nophoto.png")}}"
                            @endif

                            height="40" width="40" data-fancybox="Site Ayarları" data-caption="Footer Logo"
                            alt>
                    </div>

                    <label for="headCode"
                           title="Head etiketi içersine eklemek istediğiniz kodları buraya yazabilirsiniz.">
                        Head Ek (?):</label>
                    <textarea name="head_code" id="headCode" cols="30" rows="10">{{$siteSettings->head_code}}</textarea>

                    <label for="headerCode"
                           title="Header etiketi içersine eklemek istediğiniz kodları buraya yazabilirsiniz.">
                        Header Ek (?):</label>
                    <textarea name="header_code" id="headerCode" cols="30"
                              rows="10">{{$siteSettings->header_code}}</textarea>

                    <label for="footerCode"
                           title="Footer etiketi içersine eklemek istediğiniz kodları buraya yazabilirsiniz.">
                        Footer Ek (?):</label>
                    <textarea name="footer_code" id="footerCode" cols="30"
                              rows="10">{{$siteSettings->footer_code}}</textarea>

                    <input type="submit" value="Kaydet">
                </form>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
