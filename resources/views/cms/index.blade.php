@extends("cms.partial.layout")

@section("content")
    <div class="card">
        <div class="card-header">
            {{env("APP_NAME")}} Sistem Analizleri
        </div>
        <div class="card-body fd-row">
            {{-- Kullanıcı --}}
            @include('cms.partial.dashboard-card', [
                'title' => 'Kullanıcı Girişleri',
                'color' => 'danger',
                'data' => $kullaniciSayilari
            ])

            {{-- WhatsApp --}}
            @include('cms.partial.dashboard-card', [
                'title' => 'Whatsapp İletişimleri',
                'color' => 'success',
                'data' => $whatsappAramaSayilari
            ])

            {{-- Telefon --}}
            @include('cms.partial.dashboard-card', [
                'title' => 'Telefon Aramaları',
                'color' => 'warning',
                'data' => $telefonAramaSayilari
            ])

            {{-- Toplam Mailler --}}
            @include('cms.partial.dashboard-card', [
                'title' => 'Toplam Gelen Mailler',
                'color' => 'primary',
                'data' => $toplamMailVerisi
            ])
        </div>
    </div>
    @include('cms.partial.weekly-report-table', ['weeklyReportData' => $weeklyReportData])

    @include('cms.partial.monthly-report-table', [
        'reportData' => $monthlyReportData
    ])

    {{-- 1. TABLO: ŞEHİR BAZLI RAPOR (MAVİ/FERAH TEMA) --}}
    <div class="report-card-light theme-city">
        <div class="report-header">
            Şehir Bazlı Kullanıcı Verileri (81 İL)
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th class="text-left">ŞEHİR</th>
                    <th class="text-center" style="color:#6f42c1;">KULLANICI</th>
                    <th class="text-center" style="color:#198754;">WHATSAPP</th>
                    <th class="text-center" style="color:#dc3545;">TELEFON</th>
                    <th class="text-center" style="color:#0d6efd;">MAİLLER</th>
                </tr>
                </thead>
                <tbody>
                @foreach($detailedCityData as $index => $data)
                    <tr>
                        <td style="color: #888;">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $data['isim'] }}</td>

                        <td class="text-center">
                            @if($data['kullanici'] > 0)
                                <span class="big-num">{{ $data['kullanici'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['whatsapp'] > 0)
                                <span class="big-num">{{ $data['whatsapp'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['telefon'] > 0)
                                <span class="big-num">{{ $data['telefon'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['mail'] > 0)
                                <span class="big-num">{{ $data['mail'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" style="color:#fff; font-size:14px; text-transform:uppercase;">Genel Toplam</td>
                    <td class="text-center"><span class="big-num">{{ $detailedCityData->sum('kullanici') }}</span></td>
                    <td class="text-center"><span class="big-num">{{ $detailedCityData->sum('whatsapp') }}</span></td>
                    <td class="text-center"><span class="big-num">{{ $detailedCityData->sum('telefon') }}</span></td>
                    <td class="text-center"><span class="big-num">{{ $detailedCityData->sum('mail') }}</span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- 2. TABLO: ÜLKE BAZLI RAPOR (KREM/TURUNCU TEMA) --}}
    <div class="report-card-light theme-country">
        <div class="report-header">
            Ülke Bazlı Kullanıcı Verileri
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th class="text-left">ÜLKE</th>
                    <th class="text-center" style="color:#6f42c1;">KULLANICI</th>
                    <th class="text-center" style="color:#198754;">WHATSAPP</th>
                    <th class="text-center" style="color:#dc3545;">TELEFON</th>
                    <th class="text-center" style="color:#0d6efd;">MAİLLER</th>
                </tr>
                </thead>
                <tbody>
                @forelse($detailedCountryData as $index => $data)
                    <tr>
                        <td style="color: #888;">{{ $index + 1 }}</td>
                        <td class="text-left text-dark">{{ $data['isim'] }}</td>

                        <td class="text-center">
                            <span class="big-num text-dark">{{ $data['kullanici'] }}</span>
                        </td>
                        <td class="text-center">
                            @if($data['whatsapp'] > 0)
                                <span class="big-num text-dark">{{ $data['whatsapp'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['telefon'] > 0)
                                <span class="big-num text-dark">{{ $data['telefon'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($data['mail'] > 0)
                                <span class="big-num text-dark">{{ $data['mail'] }}</span>
                            @else
                                <span class="big-num c-zero">0</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: #999; font-style: italic;">Henüz ülke verisi oluşmadı.</td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" style="color:#666; font-size:14px; text-transform:uppercase;">Genel Toplam</td>
                    <td class="text-center"><span class="big-num c-purple">{{ $detailedCountryData->sum('kullanici') }}</span></td>
                    <td class="text-center"><span class="big-num c-green">{{ $detailedCountryData->sum('whatsapp') }}</span></td>
                    <td class="text-center"><span class="big-num c-red">{{ $detailedCountryData->sum('telefon') }}</span></td>
                    <td class="text-center"><span class="big-num c-blue">{{ $detailedCountryData->sum('mail') }}</span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>


    {{-- Form Kartları --}}
    <div class="card">
        <div class="card-header">Form Bazlı Kullanıcı Verileri</div>
        <div class="card-body fd-row">
            @foreach($formVerileri as $formIsmi => $veri)
                @php
                    $basliklar = [
                        'ContactForm' => 'İletişim Toplam Gelen Mailler',
                        'BalkonVitrinYarisma' => 'Balkon V. Y. Toplam Gelen Mailler',
                        'HumanResource' => 'İnsan Kaynakları Toplam Gelen Mailler',
                        'PortakalliLezzetler' => 'Portakallı Lezzetler Toplam Gelen Mailler',
                        'BultenİletisimForm' => 'Bülten İletişim Toplam Gelen Mailler',

                    ];

                    $baslik = $basliklar[$formIsmi] ?? $formIsmi . ' Toplam Gelen Mailler';
                @endphp

                @include('cms.partial.form-card', [
                'title' => $baslik,
                'color' => 'primary',
                'data' => $veri
            ])
            @endforeach
        </div>
    </div>

@endsection
