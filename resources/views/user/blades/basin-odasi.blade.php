@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>

                @if($page->image != NULL)
                    <figure class="normal"
                            @if($page->image != NULL) data-src="{{$page->image()}}"
                            @else
                                data-src="{{$page->image()}}"
                            @endif  data-fancybox="{{$page->title}}">

                        <img src="{{$page->image()}}" alt="{{$page->title}}">
                    </figure>
                @endif
                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif


                    <div class="basin-odasi">
                        @foreach($page->allChildren as $item)

                                <a href="{{$item->slug}}" class="basin">
                                    <figure>
                                        <img src="{{$item->image()}}" alt="{{$item->title}}">
                                    </figure>
                                    <h2s class="baslik">{{$item->title}}</h2s>
                                </a>

                        @endforeach
                    </div>



            </div>
            @include("user.partial.sidemenu")


        </div>
    </section>



@endsection









