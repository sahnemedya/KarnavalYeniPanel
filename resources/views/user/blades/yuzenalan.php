<style>
    /* Masaüstünde gizle */
    .mobile-fixed-footer {
        display: none;
    }

    @media (max-width: 767px) {
        .mobile-fixed-footer {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 65px;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 9999;
            padding: 5px 0;
        }

        .footer-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.3s;
        }

        /* İkon Boşluğu */
        .footer-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        /* Renk Tanımlamaları */
        .call-btn { color: #2c3e50; }
        .whatsapp-btn {
            color: #25D366;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee;
        }
        .contact-btn { color: #e67e22; }

        /* Tıklama Efekti */
        .footer-item:active {
            background-color: #f8f9fa;
        }
        .appointment-btn {
            color: #3498db; /* Yazı ve ikon beyaz */

        }
    }
</style>

<div class="mobile-fixed-footer">

    <a onclick="whatsAppAramasi(event,'https://wa.me/+9{{$contacts->socialMedia->whatsapp}}?text=Merhabalar%20Tedavi%20İle%20%İlgili%20Bilgi%20Alabilir%20Miyim?')"
       href="https://wa.me/+9{{$contacts->socialMedia->whatsapp}}?text=Merhabalar%20Tedavi%20İle%20%İlgili%20Bilgi%20Alabilir%20Miyim?"
       target="_blank" class="footer-item whatsapp-btn">
        <i class="fab fa-whatsapp"></i>
        <span>WhatsApp</span>
    </a>
    {{--    <a href="/iletisim" class="footer-item contact-btn">--}}
        {{--        <i class="fas fa-envelope"></i>--}}
        {{--        <span>İletişim</span>--}}
        {{--    </a>--}}
    <a href="/randevu-al" class="footer-item appointment-btn">
        <i class="fas fa-calendar-check"></i>
        <span>Randevu Al</span>
    </a>
    <a onclick="telefonpAramasi(event,'tel:{{$contacts->phone}}')" href="tel:{{$contacts->phone}}" class="footer-item call-btn">
        <i class="fas fa-phone-alt"></i>
        <span>Hemen Ara</span>
    </a>
</div>
