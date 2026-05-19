@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width menulu">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>


                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif


                <div class="fotograf-arsivi">
                    @foreach($page->allChildren->sortByDesc('hit') as $item)
                            <a href="{{$item->slug}}" class="arsiv">
                                <figure>
                                    @if(($item->image!=NULL))
                                        <img src="{{$item->image()}}" alt="{{$item->title}}">
                                    @else
                                        <img src="{{asset("images/user/nophoto.jpg")}}" alt="{{$item->title}}">
                                    @endif
                                </figure>
                                <div class="baslik">
                                    <h2>{{$item->title}}</h2>
                                </div>
                            </a>
                    @endforeach
                </div>

                @if($sss->isNotEmpty())
                    <div class="container-faq fade-in">

                        <div class="header">
                            <h2>Sıkça Sorulan Sorular</h2>
                        </div>

                        <div class="faq-container">

                            @foreach($sss as $item)
                                <div class="faq-item">
                                    <button class="faq-question">
                                        {{$item->question}}
                                        <div class="plus-icon">+</div>
                                    </button>
                                    <div class="faq-answer">
                                        {!! $item->answer !!}
                                    </div>
                                </div>
                            @endforeach


                        </div>

                    </div>
                @endif




            </div>
            @include("user.partial.sidemenu")


    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(question => {
                question.addEventListener('click', function () {
                    const faqItem = this.parentElement;
                    const faqAnswer = faqItem.querySelector('.faq-answer');
                    const isActive = this.classList.contains('active');

                    // Tüm açık soruları kapat
                    faqQuestions.forEach(q => {
                        q.classList.remove('active');
                        q.parentElement.querySelector('.faq-answer').classList.remove('active');
                    });

                    // Eğer tıklanan soru kapalıysa, aç
                    if (!isActive) {
                        this.classList.add('active');
                        faqAnswer.classList.add('active');

                        // Smooth scroll to the opened question
                        setTimeout(() => {
                            faqItem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 100);
                    }
                });
            });

            // Klavye erişilebilirliği
            faqQuestions.forEach(question => {
                question.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        });
    </script>

@endsection








