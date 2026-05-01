<div class="card-4">
    <div class="sb-card stat-summary-card border-left-{{ $color }}">
        <div class="card-content">

            <div class="stat-summary-header">
                <div class="stat-info">
                    <div class="stat-title text-{{ $color }}">{{ $title }}</div>

                </div>

                <div class="stat-icon text-{{ $color }}">
                    @if($color == 'danger')
                        <i class="las la-user-check"></i>
                    @elseif($color == 'success')
                        <i class="lab la-whatsapp"></i>
                    @elseif($color == 'warning')
                        <i class="las la-phone"></i>
                    @else
                        <i class="las la-envelope"></i>
                    @endif
                </div>
            </div>

            <ul class="stat-breakdown-list">
                <li>
                    <span>Son 3 Gün:</span>
                    <span>{{ $data['son_3_gun'] }}</span>
                </li>
                <li>
                    <span>Son 7 Gün:</span>
                    <span>{{ $data['son_7_gun'] }}</span>
                </li>
                <li>
                    <span>Son 1 Ay:</span>
                    <span>{{ $data['son_1_ay'] }}</span>
                </li>
                <li>
                    <span>Son 3 Ay:</span>
                    <span>{{ $data['son_3_ay'] }}</span>
                </li>
                <li>
                    <span>Son 6 Ay:</span>
                    <span>{{ $data['son_6_ay'] }}</span>
                </li>
                <li>
                    <span>Son 9 Ay:</span>
                    <span>{{ $data['son_9_ay'] }}</span>
                </li>
                <li>
                    <span>Son 12 Ay:</span>
                    <span>{{ $data['son_12_ay'] }}</span>
                </li>

                <li class="total-row">
                    <span>Toplam:</span>
                    <span>{{ $data['tum_mail'] ?? $data['tum_kullanici'] ?? $data['tum_whatsapp_aramalari'] ?? 0 }}</span>
                </li>
            </ul>

        </div>
    </div>
</div>
