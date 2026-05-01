<div class="monthly-report-card">
    <div class="card-header">
        {{ $monthlyReportData['month_name'] }} Ayı Günlük Veri Dökümü
    </div>
    <div class="card-body">
        <div class="monthly-report-table-wrapper" id="monthly-report-wrapper">
            <table class="monthly-report-table" id="monthly-report-table">
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
                @forelse($monthlyReportData['days_data'] as $day)
                    <tr class="report-row">
                        <td>
                            <span class="report-date">{{ $day['date_formatted'] }}</span>
                            <span class="report-day-name">{{ $day['day_name'] }}</span>
                        </td>
                        <td data-label="Kullanıcı">{{ $day['user_logins'] }}</td>
                        <td data-label="WhatsApp">{{ $day['whatsapp'] }}</td>
                        <td data-label="Telefon">{{ $day['phone'] }}</td>
                        <td data-label="Mailler">{{ $day['mails'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Bu ay içinde veri bulunamadı.</td>
                    </tr>
                @endforelse
                </tbody>
                {{-- FOOTER GÜNCELLENDİ: Hem bu ayı hem geçen ayı gösteriyoruz --}}
                <tfoot>
                {{-- 1. Satır: Mevcut Ayın Toplamı --}}
                <tr>
                    <th>Toplam</th>
                    <td>{{ $monthlyReportData['totals']['total_logins'] }}</td>
                    <td>{{ $monthlyReportData['totals']['total_whatsapp'] }}</td>
                    <td>{{ $monthlyReportData['totals']['total_phone'] }}</td>
                    <td>{{ $monthlyReportData['totals']['total_mails'] }}</td>
                </tr>

                {{-- 2. Satır: Bir Önceki Ayın Toplamı (Yeni Veri) --}}
                <tr class="prev-month-row" style="opacity: 0.7; border-top: 1px dashed rgba(255,255,255,0.1);">
                    <th>{{ $monthlyReportData['previous_month']['name'] }} Toplam</th>
                    <td>{{ $monthlyReportData['previous_month']['total_users'] }}</td>
                    <td>{{ $monthlyReportData['previous_month']['total_whatsapp'] }}</td>
                    <td>{{ $monthlyReportData['previous_month']['total_phone'] }}</td>
                    <td>{{ $monthlyReportData['previous_month']['total_mails'] }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
