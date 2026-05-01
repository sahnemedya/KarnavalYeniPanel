@extends('user.partial.master')
@section('content')

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1 style="letter-spacing: 1px">{{ $page->title }}</h1>
                {!! $page->content !!}

                <div class="videolar">

                    @isset($apiKeys->youtube_channel_id)
                        @php
                            // --- AYARLAR ---
                            // Yeni API Anahtarınız eklendi:
                            $myApiKey = "AIzaSyDsEV2aCDKpL-B6Z5B4eMo5NXgZj63dtCI";

                            $myChannelID = $apiKeys->youtube_channel_id;
                            $maxResults = "30";

                            // API Sorgu Adresi
                            $myQuery = "https://www.googleapis.com/youtube/v3/search?key=$myApiKey&channelId=$myChannelID&part=snippet,id&order=date&maxResults=$maxResults";

                            // Veri Çekme Fonksiyonu
                            if (!function_exists('url_get_contents')) {
                                function url_get_contents($Url)
                                {
                                    if (!function_exists('curl_init')) {
                                        return false; // Hata durumunda siteyi öldürme, false dön.
                                    }
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $Url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL hatalarını önlemek için
                                    $output = curl_exec($ch);
                                    curl_close($ch);
                                    return $output;
                                }
                            }

                            // Veriyi Çek
                            $videoList = url_get_contents($myQuery);

                            // JSON'u Diziye Çevir
                            $decoded = json_decode($videoList, true);
                        @endphp

                        {{-- HATA KONTROLÜ BAŞLANGICI --}}
                        {{-- Eğer $decoded doluysa ve içinde 'items' varsa döngüye gir --}}
                        @if(isset($decoded['items']) && is_array($decoded['items']))

                            @foreach ($decoded['items'] as $items)
                                @php
                                    $id = @$items['id']['videoId'];
                                    $titleRaw = isset($items['snippet']['title']) ? $items['snippet']['title'] : '';
                                    $title2 = mb_convert_case(substr($titleRaw, 0, 80), MB_CASE_TITLE, "UTF-8");
                                @endphp

                                @if (isset($id))
                                    <div class="video">
                                        <iframe width="100%" height="250"
                                                src="https://www.youtube.com/embed/{{ $id }}"
                                                title="{{ env('APP_NAME') }}"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                referrerpolicy="strict-origin-when-cross-origin"
                                                allowfullscreen>
                                        </iframe>
                                        <h3>{{ $title2 }}</h3>
                                    </div>
                                @endif
                            @endforeach

                        @else
                            {{-- API Çalışmazsa veya Kota Dolarsa Burası Görünür --}}
                            <div style="width:100%; text-align:center; padding: 20px; color: #666;">
                                <p>Videolar şu anda görüntülenemiyor. Lütfen daha sonra tekrar ziyaret ediniz.</p>
                            </div>
                        @endif
                        {{-- HATA KONTROLÜ BİTİŞİ --}}

                    @endisset
                </div>

            </div>
        </div>
    </section>

@endsection
