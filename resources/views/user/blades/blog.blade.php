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

                @if ($page->content != null)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif


                <div class="lezzetler">
                    @foreach ($page->allChildrenBlog as $item)
                        <a href="{{ $item->slug }}" class="lezzet">
                            <figure>
                                <img src="{{ $item->image() }}" alt="{{ $item->title }}">
                            </figure>
                            <h2 class="baslik">{{ $item->title }}</h2>
                        </a>
                    @endforeach

                </div>


            </div>

            @include('user.partial.sidemenu')
        </div>
    </section>
@endsection
