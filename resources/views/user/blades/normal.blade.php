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



                <div class="extrem">
                    @if($page->ses)
                        <audio controls="" style="width: 100%">
                            <source src="{{$page->ses()}}"/>
                        </audio>
                    @endif

                    @if($page->video)
                        <iframe
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen="" frameborder="0" height="768px"
                            referrerpolicy="strict-origin-when-cross-origin" src="{{$page->video}}"
                            title="YouTube video player" width="100%"></iframe>
                    @endif

                    @if($page->file)
                        <a download="{{$page-> title}}" href="{{$page->file()}}"
                           style="background-color:#e28337 !important; font-size: 20px; line-height: 20px;  color:#fff; padding: 5px 10px; border-radius: 34px; margin-bottom: 20px; font-weight: 600;">Dosyayı
                            indirmek için tıklayınız. </a>

                    @endif

                    @if($page->link)
                        <a download="{{$page-> title}} - 2" href="{{$page->link()}}"
                           style="background-color:#e28337 !important; font-size: 20px; line-height: 20px;  color:#fff; padding: 5px 10px; border-radius: 34px; margin-bottom: 20px; font-weight: 600;">Dosyayı
                            indirmek için tıklayınız. </a>

                    @endif

                    @if($page->heyzen)
                        <iframe allow="clipboard-write" allowfullscreen="allowfullscreen" class="fp-iframe" scrolling="no"
                                src="{{$page->heyzen}}"
                                style="border: 1px solid lightgray; width: 100%; height: 400px;"></iframe>
                    @endif

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



                @if($page->gallery->count()>0)
                    <h2>{{ $page->inside_title ?? $page->title }} Foto Galeri</h2>
                    <div class="galeri">
                        @foreach($page->gallery as $galeri)
                            <figure class="galeri-figure" data-fancybox="gallery" data-src="{{$galeri->image()}}"
                                    data-caption="{{$galeri->baslik}} - {{$loop->index+1}}">
                                <img src="{{$galeri->image()}}"
                                     alt="{{$galeri->name}} Galeri Resmi {{$loop->index}}">
                            </figure>
                        @endforeach
                    </div>
                @endif


            </div>
            @include("user.partial.sidemenu")


        </div>
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








