@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>


                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif

                <iframe allowfullscreen="allowfullscreen" class="fp-iframe" scrolling="no" src="{{ $page->heyzen }}" style="border: 1px solid lightgray; width: 100%; height: 768px;"></iframe>

            </div>



        </div>
    </section>



@endsection









