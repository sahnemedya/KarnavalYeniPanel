@extends('user.partial.master')
@section('content')

    <style>
        .sponsorluk-sunumu {

            margin-bottom: 65px;
        }

        .sponsorluk-sunumu h2 {
            width: 100%;
            text-align: center;
            color: #efa506;
            font-size: 2.6rem;
            line-height: 3.9000000000000004rem;
            font-weight: bold;
        }

        .sponsorluk-sunumu p {
            width: 100%;
            text-align: center;
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .content-space {
            padding: 55px 0 65px 0;
        }

        .max-width {min-height:unset!important;
            max-width: 1440px !important;
            margin: 0 auto;
            padding-left: 25px !important;
            padding-right: 25px !important;
        }

        .web-none {
            display: none;
            @media (max-width: 768px) {
                display: block !important;
            }
        }

        .mobil-none {
            display: block;
            @media (max-width: 768px) {
                display: none !important;
            }
        }

        .gecen-sene-ozet {
            width: 100%;

            img {
                width: 100%;
            }
        }

        .iletisim-butonlari {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap:wrap;
            gap: 15px;
        }

        .iletisim-butonlari .iletisim-buton {
            max-width: calc((100% - 60px) / 5);
            width: 100%;
            background: #ffffff;
            padding: 7px 10px;
            color: #efefef;
            font-weight: 500;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        @media (max-width: 768px) {
            .iletisim-butonlari .iletisim-buton {
                max-width: calc((100% - 30px) / 3);
            }
        }
        @media (max-width: 500px) {
            .iletisim-butonlari .iletisim-buton {
                max-width: 100%;
            }
        }
        .iletisim-butonlari .iletisim-buton.tel {
            background-color: #03a29c
        }

        .iletisim-butonlari .iletisim-buton.mail {
            background-color: #156ed6
        }

        .iletisim-butonlari .iletisim-buton.wp {
            background-color: #1da960
        }

        .iletisim-butonlari .iletisim-buton.pr {
            background-color: #ee7e23;
            margin-top: 25px;
        }

        .owl-2024-sponsorlarimiz .item span {
            font-size: 14px;
            color: #263145;
            width: 100%;
            text-align: center;
            display: inline-block;
        }

        .sponsorlarimiz-list {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 25px 0 50px 0;
        }

        .sponsorlarimiz-buton {
            text-align: center;
            color: #ffffff;
            background-color: #efa506;

            font-size: 2.6rem;
            line-height: 3.9000000000000004rem;
            font-weight: bold;
            padding: 10px 15px;
            transition: all .3s ease-in-out;
        }
        .sponsorlarimiz-buton:hover{
            color: #ffffff;
            background-color: #1f2a3f;
        }

        .owl-carousel{
            position: relative;
        }
        .owl-nav{
            position: absolute;
            top:0;
            left:0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }

        .owl-nav button span{
            width: 30px;
            height: 30px;
            font-size: 22px;
            background-color: #202a3f70;
            color:#ee7e23;
            padding: 2px;
        }
        .owl-nav button span:hover{
            color: #ffffff;
        }
    </style>

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1>{{ $page->inside_title ?? $page->title }}</h1>


                @if($page->content != NULL)
                    {!! $page->content !!}
                @else
                    <p><em><strong>@lang('ortakMetinler.guncelleniyor')</strong></em></p>
                @endif


            </div>


        </div>
    </section>

@endsection








