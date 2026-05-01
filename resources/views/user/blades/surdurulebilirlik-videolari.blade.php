@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1 style="letter-spacing: 1px">{{ $page->title }}</h1>
                {!! $page->content !!}

                @if ($page->pageVideos->count() > 0)
                    <div class="videolarx">
                        @foreach ($page->pageVideos as $video)
                            <div class="videox">

                                <a href="{{ $video->watchUrl() }}"
                                   data-fancybox="{{ $page->title }}"
                                   target="_blank"
                                   rel="noopener">

                                    <figure>
                                        @if ($video->coverImage())
                                            <img src="{{ $video->coverImage() }}" alt="{{ $video->title }}" loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-thumbnail.jpg') }}" alt="{{ $video->title }}" loading="lazy">
                                        @endif
                                    </figure>

                                </a>

                                <h2 title="{{ $video->title }}">
                                    {{ $video->title }}
                                </h2>

                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
            @include('user.partial.sidemenu')
        </div>
    </section>

@endsection
