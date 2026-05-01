<div class="stats-card">
    <div class="stats-card-header">
        <h3>Haftalık Veri Dökümü</h3>
        {{-- İstersen buraya tarih aralığını yazdırabilirsin --}}
        <small>{{ $weeklyReportData['week_start'] }} - {{ $weeklyReportData['week_end'] }}</small>
    </div>
    <div class="stats-card-body">
        <table class="stats-table">
            <thead>
            <tr>
                <th>Gün</th>
                <th>Kullanıcı Girişi</th>
                <th>WhatsApp</th>
                <th>Telefon</th>
                <th>Mailler</th>
            </tr>
            </thead>
            <tbody>
            {{-- HAFTALIK GÜNLER (Her hafta sıfırlanır, Pazartesi'den Pazar'a döner) --}}
            @foreach($weeklyReportData['days_data'] as $day)
                <tr class="stats-table-row">
                    <td>
                        <span class="day-label">{{ $day['day_name'] }}</span>
                    </td>
                    <td data-label="Kullanıcı">{{ $day['user_logins'] }}</td>
                    <td data-label="WhatsApp">{{ $day['whatsapp'] }}</td>
                    <td data-label="Telefon">{{ $day['phone'] }}</td>
                    <td data-label="Mailler">{{ $day['mails'] }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>Genel Toplam</th> {{-- SIFIRLANMAYAN TOPLAM --}}
                <td>{{ $weeklyReportData['totals']['total_logins'] }}</td>
                <td>{{ $weeklyReportData['totals']['total_whatsapp'] }}</td>
                <td>{{ $weeklyReportData['totals']['total_phone'] }}</td>
                <td>{{ $weeklyReportData['totals']['total_mails'] }}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
