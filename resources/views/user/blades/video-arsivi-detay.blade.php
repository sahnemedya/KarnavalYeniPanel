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

                @if($page->video)
                    <div class="videolar">
                        @php
                            // YouTube Data API'den oynatma listesindeki TÜM videoları alma
                            $myApiKey = "AIzaSyCNisNkKMQSCCuuK0YBzAJvxgnb2EkdQ70"; // API anahtarın
                            $playListId = $page->video; // Oynatma listesi ID'si
                            $maxResults = 50; // API'nin izin verdiği en fazla değer
                            $videos = [];
                            $nextPageToken = '';

                            do {
                                $myQuery = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=$playListId&key=$myApiKey&maxResults=$maxResults&pageToken=$nextPageToken";
                                $response = file_get_contents($myQuery);
                                if ($response === FALSE) {
                                    die('API isteği başarısız oldu.');
                                }
                                $data = json_decode($response, true);

                                if (isset($data['items'])) {
                                    $videos = array_merge($videos, $data['items']);
                                }

                                $nextPageToken = $data['nextPageToken'] ?? '';
                            } while (!empty($nextPageToken));

                            // Videoları tarihe göre sırala (en yeni en üstte)
                            usort($videos, function($a, $b) {
                                return strtotime($b['snippet']['publishedAt']) - strtotime($a['snippet']['publishedAt']);
                            });
                        @endphp

                        @foreach($videos as $item)
                            @php
                                $title = $item['snippet']['title'];
                                $videoId = $item['snippet']['resourceId']['videoId'] ?? null;
                                $thumbnail = $item['snippet']['thumbnails']['medium']['url']
                                            ?? $item['snippet']['thumbnails']['default']['url']
                                            ?? asset('images/default-thumbnail.jpg');
                            @endphp
                            @if($videoId)
                                <div class="video">
                                    <figure>
                                        <img src="{{ $thumbnail }}" alt="{{ $title }}">
                                    </figure>
                                    <h2 title="{{ $title }}">{{ \Illuminate\Support\Str::limit($title, 70, '...') }}</h2>
                                    <a href="https://www.youtube.com/watch?v={{ $videoId }}" data-fancybox="{{ $page->baslik }}" target="_blank">
                                        Videoyu İzle
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif


            </div>



        </div>
    </section>



@endsection









