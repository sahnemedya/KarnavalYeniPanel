@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>

                @if($page->image != NULL)
                    <figure class="normal"
                            @if($page->image != NULL) data-src="{{$page->image()}}"
                            @else
                                data-src="{{$page->image()}}"
                            @endif  data-fancybox="{{$page->title}}">

                        <img src="{{$page->image()}}" alt="">
                    </figure>
                @endif
                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif

                <div class="gecmis-seneler">
                    @foreach($page->allChildren as $item)
                        <a href="{{$item->slug}}" class="sene">
                            <figure>
                                @if(($item->image!=NULL))
                                    <img src="{{$item->image()}}" alt="{{$item->title}}">
                                @else
                                    <img src="{{asset("images/user/nophoto.jpg")}}" alt="{{$item->title}}">
                                @endif
                            </figure>
                            <div class="text">
                                <h2>{{$item->title}}</h2>
                            </div>
                        </a>
                    @endforeach
                </div>


            </div>



        </div>
    </section>



@endsection









