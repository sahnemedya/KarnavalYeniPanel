@extends('user.partial.master')
@section('content')
    <style>
        .onay-konteynir {
            margin: 20px 0;
            padding: 10px;
            background: #fdfdfd;
            border-radius: 5px;
        }

        .onay-satiri {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.4;
        }

        .onay-satiri input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            margin-top: 2px;
            cursor: pointer;
        }

        .onay-satiri label a {
            color: #d93025;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Modal Genel Stili */
        .yarisma-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-icerik {
            background-color: #fff;
            margin: 5% auto;
            padding: 0;
            width: 85%;
            max-width: 750px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            animation: slideIn 0.3s;
        }

        .modal-baslik {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }

        .modal-baslik h3 {
            font-size: 16px;
            color: #333;
            margin: 0;
        }

        .modal-govde {
            padding: 25px;
            max-height: 450px;
            overflow-y: auto;
            color: #555;
            font-size: 14px;
            line-height: 1.6;
            text-align: justify;
        }

        .kapat {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .kapat:hover {
            color: #000;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .yonerge {
            background-color: #e28337;
            padding: 5px 20px;
            border-radius: 14.5px;
            color: #FFFFFF;
        }

        .yonerge:hover {
            background-color: rgba(255, 121, 12, 1)
        }
    </style>

    <section class="normal-sayfa content-space">
        <div class="max-width">
            <div class="text">

                <h1>{{ $page->title }}</h1>

                @if ($page->image != null)
                    <figure class="normal"
                            @if ($page->image != null) data-src="{{ $page->image() }}"
                            @else
                                data-src="{{ $page->image() }}" @endif
                            data-fancybox="{{ $page->title }}">

                        <img src="{{ $page->image() }}" alt="">
                    </figure>
                @endif
                {!! $page->content !!}


                @if ($page->title == 'Adana Karnavalda Sizinle Güzelleşiyor!')
                    <div class="balkon-vitrin-container">

                        {{-- Üst Bilgilendirme Notu --}}

                        {{--  --}}
                        <form class="iletisim-formu" action="{{ route('yarismaBasvuruPost') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="g_recaptcha_response" id="securitytoken" value="">

                            {{-- Kategori Seçimi - Tam Genişlik --}}
                            <div class="form-group section-bg item100">
                                <label class="section-label">Başvuru Türü Seçiniz (*)</label>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="tur" value="vitrin" id="type_vitrin"
                                               onclick="toggleFields()" required>
                                        <span>Vitrin Yarışması</span>
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="tur" value="balkon" id="type_balkon"
                                               onclick="toggleFields()" required>
                                        <span>Balkon Yarışması</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Vitrin Özel Alan - Tam Genişlik --}}
                            <div id="vitrin_fields" class="conditional-field item100" style="display: none;">
                                <input class="item100" type="text" name="isletme_adi"
                                       placeholder="Mağaza / İşletme Adı (*)">
                            </div>

                            {{-- Ad Soyad ve E-posta - Yan Yana (%50) --}}
                            <input class="item" type="text" name="adSoyad" id="person_label"
                                   placeholder="Adınız Soyadınız (*)" required>
                            <input class="item" type="email" name="email" placeholder="E-posta Adresiniz (*)"
                                   required>

                            <select class="item" name="ulkeKodu" id=""
                                    @if ($errors->first('ulkeKodu')) style="border:4px solid red"
                                    @endif required="required">
                                <option data-countryCode="TR" disabled>Ülke Kodu</option>
                                <option data-countryCode="TR" value="90">Türkiye (+90)</option>
                                <option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                <option data-countryCode="AD" value="376">Andorra (+376)</option>
                                <option data-countryCode="AO" value="244">Angola (+244)</option>
                                <option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                <option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                <option data-countryCode="AR" value="54">Argentina (+54)</option>
                                <option data-countryCode="AM" value="374">Armenia (+374)</option>
                                <option data-countryCode="AW" value="297">Aruba (+297)</option>
                                <option data-countryCode="AU" value="61">Australia (+61)</option>
                                <option data-countryCode="AT" value="43">Austria (+43)</option>
                                <option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                <option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                <option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                <option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                <option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                <option data-countryCode="BY" value="375">Belarus (+375)</option>
                                <option data-countryCode="BE" value="32">Belgium (+32)</option>
                                <option data-countryCode="BZ" value="501">Belize (+501)</option>
                                <option data-countryCode="BJ" value="229">Benin (+229)</option>
                                <option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                <option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                <option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                <option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                <option data-countryCode="BW" value="267">Botswana (+267)</option>
                                <option data-countryCode="BR" value="55">Brazil (+55)</option>
                                <option data-countryCode="BN" value="673">Brunei (+673)</option>
                                <option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                <option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                <option data-countryCode="BI" value="257">Burundi (+257)</option>
                                <option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                <option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                <option data-countryCode="CA" value="1">Canada (+1)</option>
                                <option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                <option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                <option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                <option data-countryCode="CL" value="56">Chile (+56)</option>
                                <option data-countryCode="CN" value="86">China (+86)</option>
                                <option data-countryCode="CO" value="57">Colombia (+57)</option>
                                <option data-countryCode="KM" value="269">Comoros (+269)</option>
                                <option data-countryCode="CG" value="242">Congo (+242)</option>
                                <option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                <option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                <option data-countryCode="HR" value="385">Croatia (+385)</option>
                                <option data-countryCode="CU" value="53">Cuba (+53)</option>
                                <option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                <option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                <option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                <option data-countryCode="DK" value="45">Denmark (+45)</option>
                                <option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                <option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                <option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                <option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                <option data-countryCode="EG" value="20">Egypt (+20)</option>
                                <option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                <option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                <option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                <option data-countryCode="EE" value="372">Estonia (+372)</option>
                                <option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                <option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                <option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                <option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                <option data-countryCode="FI" value="358">Finland (+358)</option>
                                <option data-countryCode="FR" value="33">France (+33)</option>
                                <option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                <option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                <option data-countryCode="GA" value="241">Gabon (+241)</option>
                                <option data-countryCode="GM" value="220">Gambia (+220)</option>
                                <option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                <option data-countryCode="DE" value="49">Germany (+49)</option>
                                <option data-countryCode="GH" value="233">Ghana (+233)</option>
                                <option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                <option data-countryCode="GR" value="30">Greece (+30)</option>
                                <option data-countryCode="GL" value="299">Greenland (+299)</option>
                                <option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                <option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                <option data-countryCode="GU" value="671">Guam (+671)</option>
                                <option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                <option data-countryCode="GN" value="224">Guinea (+224)</option>
                                <option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                <option data-countryCode="GY" value="592">Guyana (+592)</option>
                                <option data-countryCode="HT" value="509">Haiti (+509)</option>
                                <option data-countryCode="HN" value="504">Honduras (+504)</option>
                                <option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                <option data-countryCode="HU" value="36">Hungary (+36)</option>
                                <option data-countryCode="IS" value="354">Iceland (+354)</option>
                                <option data-countryCode="IN" value="91">India (+91)</option>
                                <option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                <option data-countryCode="IR" value="98">Iran (+98)</option>
                                <option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                <option data-countryCode="IE" value="353">Ireland (+353)</option>
                                <option data-countryCode="IL" value="972">Israel (+972)</option>
                                <option data-countryCode="IT" value="39">Italy (+39)</option>
                                <option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                <option data-countryCode="JP" value="81">Japan (+81)</option>
                                <option data-countryCode="JO" value="962">Jordan (+962)</option>
                                <option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                <option data-countryCode="KE" value="254">Kenya (+254)</option>
                                <option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                <option data-countryCode="KP" value="850">Korea North (+850)</option>
                                <option data-countryCode="KR" value="82">Korea South (+82)</option>
                                <option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                <option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                <option data-countryCode="LA" value="856">Laos (+856)</option>
                                <option data-countryCode="LV" value="371">Latvia (+371)</option>
                                <option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                <option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                <option data-countryCode="LR" value="231">Liberia (+231)</option>
                                <option data-countryCode="LY" value="218">Libya (+218)</option>
                                <option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                <option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                <option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                <option data-countryCode="MO" value="853">Macao (+853)</option>
                                <option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                <option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                <option data-countryCode="MW" value="265">Malawi (+265)</option>
                                <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                <option data-countryCode="MV" value="960">Maldives (+960)</option>
                                <option data-countryCode="ML" value="223">Mali (+223)</option>
                                <option data-countryCode="MT" value="356">Malta (+356)</option>
                                <option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                <option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                <option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                <option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                <option data-countryCode="MX" value="52">Mexico (+52)</option>
                                <option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                <option data-countryCode="MD" value="373">Moldova (+373)</option>
                                <option data-countryCode="MC" value="377">Monaco (+377)</option>
                                <option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                <option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                <option data-countryCode="MA" value="212">Morocco (+212)</option>
                                <option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                <option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                <option data-countryCode="NA" value="264">Namibia (+264)</option>
                                <option data-countryCode="NR" value="674">Nauru (+674)</option>
                                <option data-countryCode="NP" value="977">Nepal (+977)</option>
                                <option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                <option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                <option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                <option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                <option data-countryCode="NE" value="227">Niger (+227)</option>
                                <option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                <option data-countryCode="NU" value="683">Niue (+683)</option>
                                <option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                <option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                <option data-countryCode="NO" value="47">Norway (+47)</option>
                                <option data-countryCode="OM" value="968">Oman (+968)</option>
                                <option data-countryCode="PW" value="680">Palau (+680)</option>
                                <option data-countryCode="PA" value="507">Panama (+507)</option>
                                <option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                <option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                <option data-countryCode="PE" value="51">Peru (+51)</option>
                                <option data-countryCode="PH" value="63">Philippines (+63)</option>
                                <option data-countryCode="PL" value="48">Poland (+48)</option>
                                <option data-countryCode="PT" value="351">Portugal (+351)</option>
                                <option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                <option data-countryCode="QA" value="974">Qatar (+974)</option>
                                <option data-countryCode="RE" value="262">Reunion (+262)</option>
                                <option data-countryCode="RO" value="40">Romania (+40)</option>
                                <option data-countryCode="RU" value="7">Russia (+7)</option>
                                <option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                <option data-countryCode="SM" value="378">San Marino (+378)</option>
                                <option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                <option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                <option data-countryCode="SN" value="221">Senegal (+221)</option>
                                <option data-countryCode="CS" value="381">Serbia (+381)</option>
                                <option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                <option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                <option data-countryCode="SG" value="65">Singapore (+65)</option>
                                <option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                <option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                <option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                <option data-countryCode="SO" value="252">Somalia (+252)</option>
                                <option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                <option data-countryCode="ES" value="34">Spain (+34)</option>
                                <option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                <option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                <option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                <option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                <option data-countryCode="SD" value="249">Sudan (+249)</option>
                                <option data-countryCode="SR" value="597">Suriname (+597)</option>
                                <option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                <option data-countryCode="SE" value="46">Sweden (+46)</option>
                                <option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                <option data-countryCode="SI" value="963">Syria (+963)</option>
                                <option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                <option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                <option data-countryCode="TH" value="66">Thailand (+66)</option>
                                <option data-countryCode="TG" value="228">Togo (+228)</option>
                                <option data-countryCode="TO" value="676">Tonga (+676)</option>
                                <option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                <option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                <option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                <option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                <option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                <option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                <option data-countryCode="UG" value="256">Uganda (+256)</option>
                                <option data-countryCode="GB" value="44">UK (+44)</option>
                                <option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                <option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                <option data-countryCode="US" value="1">USA (+1)</option>
                                <option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                <option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                <option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                <option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                <option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                <option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                <option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                <option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                <option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                <option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                <option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                <option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                            </select>
                            <input class="item" type="tel" name="telefon" id=""
                                   placeholder="Telefon Numarası">


                            {{-- Grid/Flex Dengesi için Boşluk veya Ek Alan (Eğer 2. alan yoksa adres zaten aşağı kayar) --}}
                            <div class="item hide-mobile"></div>

                            {{-- Adres - Tam Genişlik (%100) --}}
                            <textarea class="item100" name="adres" placeholder="Tam Adresiniz (*)" rows="3"
                                      required></textarea>

                            {{-- Fotoğraf Yükleme Alanı - Tam Genişlik --}}
                            <div class="form-group photo-upload-zone item100">
                                <label class="section-label">Süsleme Fotoğrafları (En fazla 5 adet) (*)</label>
                                <input type="file" name="fotograflar[]" class="file-input" multiple accept="image/*"
                                       required>
                                <small class="helper-text">Birden fazla fotoğraf seçmek için Ctrl tuşuna basılı tutarak
                                    seçim yapınız.</small>
                            </div>

                            {{-- Yaş ve Belge Alanı - Tam Genişlik --}}
                            <div id="age_control_container" class="age-control-wrapper item100">
                                <div class="checkbox-group">
                                    <input type="checkbox" name="resit_mi" id="resit_mi" value="1" checked
                                           onclick="toggleFileField()">
                                    <label for="resit_mi">18 yaşından büyüğüm.</label>
                                </div>

                                <div id="veli_belgesi_alani" class="conditional-field"
                                     style="display: none; margin-top: 15px;">
                                    <div class="alert-box">
                                        <i class="fa fa-warning"></i>
                                        <span>Balkon yarışması başvurularında 18 yaşını doldurmuş bireyler doğrudan
                                            katılabilirler. 18 yaşından küçük bireyler ise ancak <strong>velisinin yazılı
                                                onayı ve imzalı veli izin belgesini </strong> başvuru formuna eklemek
                                            şartıyla katılabilirler. </span>
                                    </div>
                                    <label class="input-label">Veli İzin Belgesi Yükle (PDF/JPG/PNG) (*)</label>
                                    <input type="file" name="veli_izin_belgesi" id="veli_izin_input" class="item100">
                                </div>
                            </div>

                            {{-- Onay Belgeleri ve Checkboxlar --}}
                            <div class="onay-konteynir item100">

                                {{-- 1. KVKK Alanı --}}
                                <div class="onay-satiri">
                                    <input type="checkbox" name="kvkk_onay" id="kvkk_onay" required>
                                    <label for="kvkk_onay">
                                        <a href="javascript:void(0)" onclick="openModal('modal_kvkk')">KVKK AYDINLATMA
                                            METNİ</a>'ni okudum ve onaylıyorum. (*)
                                    </label>
                                </div>

                                {{-- 2. Taahhütname Alanı --}}
                                <div class="onay-satiri">
                                    <input type="checkbox" name="taahhutname_onay" id="taahhutname_onay" required>
                                    <label for="taahhutname_onay">
                                        <a href="javascript:void(0)" onclick="openModal('modal_taahhut')">VİTRİN VE
                                            BALKON
                                            SÜSLEME YARIŞMASI KATILIMCI TAAHHÜTNAMESİ</a>'ni okudum ve onaylıyorum. (*)
                                    </label>
                                </div>

                                {{-- MODALLAR (Popup İçerikleri) --}}
                                <div id="modal_kvkk" class="yarisma-modal">
                                    <div class="modal-icerik">
                                        <div class="modal-baslik">
                                            <h3>KİŞİSEL VERİLERİN KORUNMASINA İLİŞKİN AÇIK RIZA METNİ</h3>
                                            <span class="kapat" onclick="closeModal('modal_kvkk')">&times;</span>
                                        </div>
                                        <div class="modal-govde">

                                            <p>6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) kapsamında, veri
                                                sorumlusu sıfatıyla hareket eden <strong>Nisan’da Adana’da Turizm Kültür
                                                    ve
                                                    Sanat Vakfı</strong> tarafından aşağıdaki hususlarda
                                                bilgilendirildim.
                                            </p>

                                            <p><strong>İşlenen Kişisel Veriler</strong></p>

                                            <ul>
                                                <li>Ad, soyad</li>
                                                <li>İletişim bilgileri</li>
                                                <li>İşletme bilgileri</li>
                                                <li>Görsel ve işitsel kayıtlar</li>
                                            </ul>

                                            <ul>
                                                <li>Fotoğraf ve video görüntüleri</li>
                                            </ul>

                                            <p><strong>İşleme Amaçları</strong></p>

                                            <ul>
                                                <li>Yarışma başvuru sürecinin yürütülmesi</li>
                                                <li>Jüri değerlendirme sürecinin gerçekleştirilmesi</li>
                                                <li>Organizasyon planlaması</li>
                                                <li>Tanıtım ve iletişim faaliyetleri</li>
                                                <li>Kurumsal arşiv oluşturulması</li>
                                            </ul>

                                            <ul>
                                                <li>Gelecek yıllarda referans kullanımı</li>
                                            </ul>

                                            <p><strong>Açık Rıza Beyanı</strong></p>

                                            <ul>
                                                <li>Yukarıda belirtilen kişisel verilerimin;</li>
                                                <li>Fotoğraf ve video kayıtlarının alınmasına,</li>
                                                <li>Bu kayıtların dijital ve basılı mecralarda yayımlanmasına,</li>
                                                <li>Sosyal medya platformlarında paylaşılmasına,</li>
                                                <li>Tanıtım ve reklam materyallerinde kullanılmasına,</li>
                                            </ul>

                                            <ul>
                                                <li>Süresiz olarak arşivlenmesine</li>
                                            </ul>

                                            <p>açık rıza gösterdiğimi kabul ve beyan ederim.</p>

                                            <p>Verilerimin üçüncü kişilerle yalnızca organizasyon kapsamında
                                                paylaşılabileceğini ve KVKK hükümleri doğrultusunda korunacağını
                                                biliyorum.
                                            </p>

                                            <p>Dilediğim zaman yazılı başvuru ile açık rızamı geri çekme hakkım
                                                bulunduğu
                                                konusunda bilgilendirildim.</p>

                                        </div>
                                    </div>
                                </div>

                                <div id="modal_taahhut" class="yarisma-modal">
                                    <div class="modal-icerik">
                                        <div class="modal-baslik">

                                            <span class="kapat" onclick="closeModal('modal_taahhut')">&times;</span>
                                        </div>
                                        <div class="modal-govde">
                                            <p><strong>VİTRİN VE BALKON SÜSLEME YARIŞMASI KATILIMCI
                                                    TAAHHÜTNAMESİ</strong>
                                            </p>
                                            <p><strong>Nisan’da Adana’da Turizm Kültür ve Sanat Vakfı’na</strong></p>

                                            <p>Nisan’da Adana’da Uluslararası Portakal Çiçeği Karnavalı kapsamında
                                                düzenlenen <strong>Vitrin ve Balkon Süsleme Yarışması</strong>’na
                                                katılım
                                                sağladığımı beyan ederim.</p>

                                            <p>Aşağıdaki hususları kabul, beyan ve taahhüt ederim:</p>

                                            <p><strong>1. Yarışma Kurallarına Uyum</strong></p>

                                            <p>Yarışma yönergesini okuduğumu, anladığımı ve tüm hükümlerine uygun
                                                hareket
                                                edeceğimi kabul ederim.</p>

                                            <p><strong>2. Fikri ve Sınai Haklar</strong></p>

                                            <p>Yarışma kapsamında hazırladığım vitrin/balkon tasarımında;</p>

                                            <ul>
                                                <li>Üçüncü kişilere ait marka, logo, tasarım, görsel veya telifli
                                                    unsurları
                                                    izinsiz kullanmadığımı,
                                                </li>
                                                <li>Kullanmış olmam halinde gerekli izinleri aldığımı,</li>
                                            </ul>

                                            <ul>
                                                <li>Aksi durumda doğabilecek tüm hukuki ve cezai sorumluluğun tarafıma
                                                    ait
                                                    olduğunu
                                                </li>
                                            </ul>

                                            <p>kabul ederim.</p>

                                            <p><strong>3. Görüntüleme ve Yayın İzni</strong></p>

                                            <p>Vitrinimin/balkonumun fotoğraf ve video kaydının alınmasına ve bu
                                                kayıtların;
                                            </p>

                                            <ul>
                                                <li>Basılı ve dijital mecralarda,</li>
                                                <li>Sosyal medya platformlarında,</li>
                                                <li>Kurumsal rapor ve sunumlarda,</li>
                                            </ul>

                                            <ul>
                                                <li>Gelecek yıllara ait tanıtımlarda</li>
                                            </ul>

                                            <p>süre, yer ve mecra sınırlaması olmaksızın kullanılmasına izin verdiğimi
                                                kabul
                                                ederim.</p>

                                            <p><strong>4. Üçüncü Kişi Hakları</strong></p>

                                            <p>Süsleme alanımda yer alan kişiler, figürler, görseller veya objeler
                                                nedeniyle
                                                doğabilecek üçüncü kişi taleplerinden Vakfın ve hizmet sağlayıcıların
                                                sorumlu tutulamayacağını kabul ederim.</p>

                                            <p><strong>5. Değerlendirme Yetkisi</strong></p>

                                            <p>Jüri değerlendirmesinin nihai olduğunu ve itiraz hakkım bulunmadığını
                                                kabul
                                                ederim.</p>

                                            <p><strong>6. Beyan</strong></p>

                                            <p>Yarışmaya e-posta başvuru kanalı aracılığıyla başvurmam halinde, işbu
                                                Katılımcı Taahhütnamesi hükümlerini elektronik ortamda okuduğumu,
                                                anladığımı
                                                ve kabul ettiğimi; e-posta yoluyla yapılan başvurunun tarafımı bağlayan
                                                yazılı irade beyanı niteliğinde olduğunu kabul, beyan ve taahhüt
                                                ederim.</p>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Buton - Tam Genişlik --}}
                            <button type="submit" class="submit-btn item100 balkon-btn">BAŞVURUYU TAMAMLA</button>
                        </form>
                    </div>

                    <script>
                        function toggleFields() {
                            const isVitrin = document.getElementById('type_vitrin').checked;
                            const vitrinFields = document.getElementById('vitrin_fields');
                            const personLabel = document.getElementById('person_label');

                            if (isVitrin) {
                                vitrinFields.style.display = 'block';
                                personLabel.placeholder = "Sorumlu Kişi Adı Soyadı (*)";
                            } else {
                                vitrinFields.style.display = 'none';
                                personLabel.placeholder = "Yarışmacı Adı Soyadı (*)";
                            }
                        }

                        function toggleFileField() {
                            const isResit = document.getElementById('resit_mi').checked;
                            const fileArea = document.getElementById('veli_belgesi_alani');
                            const fileInput = document.getElementById('veli_izin_input');
                            const container = document.getElementById('age_control_container');

                            if (isResit) {
                                fileArea.style.display = 'none';
                                fileInput.required = false;
                                container.style.backgroundColor = '#f9f9f9';
                                container.style.borderColor = '#ddd';
                            } else {
                                fileArea.style.display = 'block';
                                fileInput.required = true;
                                container.style.backgroundColor = '#fff5f5';
                                container.style.borderColor = '#ffbcbc';
                            }
                        }
                    </script>

                    <script>
                        function openModal(modalId) {
                            document.getElementById(modalId).style.display = 'block';
                            document.body.style.overflow = 'hidden';
                        }

                        function closeModal(modalId) {
                            document.getElementById(modalId).style.display = 'none';
                            document.body.style.overflow = 'auto';
                        }

                        // Modal dışına tıklandığında kapanma özelliği
                        window.onclick = function (event) {
                            if (event.target.className === 'yarisma-modal') {
                                event.target.style.display = 'none';
                                document.body.style.overflow = 'auto';
                            }
                        }
                    </script>
                    <script>
                        setTimeout(function () {
                            var head = document.getElementsByTagName('head')[0];
                            var script = document.createElement('script');
                            script.type = 'text/javascript';
                            script.onload = function () {
                                grecaptcha.ready(function () {
                                    grecaptcha.execute('6Lc4vNsiAAAAAOBOiaP_tlygKCu3I3G_D2RTWhgt', {
                                        action: 'validate_captcha'
                                    }).then(function (cevap) {
                                        var formelement = document.getElementById('securitytoken');
                                        formelement.value = cevap;
                                    });
                                });
                            }
                            script.src = "https://www.google.com/recaptcha/api.js?render=6Lc4vNsiAAAAAOBOiaP_tlygKCu3I3G_D2RTWhgt";
                            head.appendChild(script);
                        }, 3000);
                    </script>
                @endif


            </div>
        </div>
    </section>
@endsection
