@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->title }}</h1>

                @if ($page->image != null)
                    <figure class="normal"
                        @if ($page->image != null) data-src="{{ $page->image() }}"
                            @else
                                data-src="{{ $page->image() }}" @endif
                        data-fancybox="{{ $page->title }}">

                        <img src="{{ $page->image() }}" alt="">
                    </figure>
                @endif
                {!! $page->content !!}



                @if ($page->gallery->count() > 0)
                    <h3>@lang('ortakMetinler.foto_galeri')</h3>
                    <div class="galeri">
                        @foreach ($page->gallery as $galeri)
                            <figure class="galeri-figure">
                                <img src="{{ $galeri->image() }}" alt="{{ $galeri->name }} Galeri Resmi {{ $loop->index }}">
                            </figure>
                        @endforeach
                    </div>
                @endif

                @if ($relatedNews && $relatedNews->count() > 0)
                    <h2>@lang('ortakMetinler.haberler_blog.diger_haberler')</h2>
                    <div class="lezzetler">
                        @foreach ($relatedNews as $item)
                            <a href="{{ $item->slug }}" class="lezzet">
                                <figure>
                                    <img src="{{ $item->image() }}" alt="{{ $item->title }}">
                                </figure>
                                <h2>{{ $item->title }}</h2>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @include('user.partial.sidemenu')


        </div>
    </section>

@endsection
