@extends('user.partial.master')
@section('content')

    <div class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">
                <div class="content-area">
                    <h1>{{$page->inside_title ?? $page->title}}</h1>
                    {!! $page->content !!}

                    <div class="sertifikalar-items">
                        @foreach($sertifikalar as $sertifika)
                            <a href="{{$sertifika->image()}}"
                               data-fancybox="{{$sertifika->name}}"
                               class="item">
                                <figure>
                                    <img src="{{$sertifika->image()}}" alt="{{$sertifika->name}}"
                                         title="{{$sertifika->name}}">
                                </figure>
                            </a>
                        @endforeach
                    </div>
                </div>


            </div>

            @include("user.partial.sidemenu")


        </div>
    </div>

@endsection








