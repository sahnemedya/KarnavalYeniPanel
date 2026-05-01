@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1 style="letter-spacing: 1px">{{ $page->title }}</h1>

                @if($page->image != NULL)
                    <figure class="normal"
                            @if($page->image != NULL) data-src="{{$page->image()}}"
                            @else
                                data-src="{{$page->image()}}"
                            @endif  data-fancybox="{{$page->title}}">

                        <img src="{{$page->image()}}" alt="{{$page->title}}">
                    </figure>
                @endif
                {!! $page->content !!}

                @if($page->gallery->count()>0)


                    <div class="galeri">
                        @foreach($page->gallery as $galeri)
                            <figure class="galeri-figure" @if($galeri->image != NULL) data-src="{{$galeri->image()}}" @else data-src="{{$galeri->image()}}" @endif  data-fancybox="{{ $galeri->page->title }}">
                                <img src="{{$galeri->image()}}"
                                     alt="{{$galeri->name}} Galeri Resmi {{$loop->index}}">
                            </figure>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
