import flatpickr from 'flatpickr';
// import Alpine from 'alpinejs';
import $ from 'jquery';  // jQuery'yi dahil et
import 'datatables.net';  // DataTables'ı dahil et
import {Fancybox} from "@fancyapps/ui";
import axios from 'axios';
import {Dropzone} from "dropzone";

flatpickr(".datepicker", {
    dateFormat: "Y-m-d",  // Tarih formatı (Örneğin: 2024-12-31)
});
flatpickr(".datetimelocalpicker", {
    enableTime: true,  // Saat seçimi ekler
    dateFormat: "Y-m-d H:i",  // Tarih ve saat formatı (Örneğin: 2024-12-31 14:30)
    locale: "tr",  // Yerel saat formatı (Türkçe saat dilimi)
    time_24hr: true,
});
flatpickr(".datetimepicker", {
    enableTime: true,  // Saat seçimi ekler
    noCalendar: false,  // Takvimi gösterir
    dateFormat: "Y-m-d H:i",  // Tarih ve saat formatı
    time_24hr: true,
});

const table = $('#datatable');
table.DataTable();  // #datatable sınıfını hedef alarak DataTables başlat

Fancybox.bind()

window.axios = axios;


let dropzone = document.getElementById('dropzone');
if (dropzone) {
    dropzone = new Dropzone("#dropzone",{
        dictDefaultMessage: "Buraya dosyaları sürükleyin veya buraya tıklayın",
        dictFallbackMessage: "Tarayıcınız sürükle bırak yüklemeyi desteklemiyor.",
        dictInvalidFileType: "Bu dosya türüne izin verilmiyor.",
        dictFileTooBig: "Dosya boyutu çok büyük",
        dictResponseError: "Sunucu hatası.",
        dictCancelUpload: "Yüklemeyi iptal et",
        dictCancelUploadConfirmation: "Bu yüklemeyi gerçekten iptal etmek istiyor musunuz?",
        dictRemoveFile: "Dosyayı sil",
        dictMaxFilesExceeded: "Başka dosya yükleyemezsiniz."
    });
    Dropzone.autoDiscover = false;
    dropzone.on("addedfile", file => {
        console.log(`File added: ${file.name}`);
    });

}

const notyf = new Notyf();

//
// window.Alpine = Alpine;
// Alpine.start();

