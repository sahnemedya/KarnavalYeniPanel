<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KarnavalSezonuRequest extends FormRequest
{
    /**
     * Kullanıcının yetkisi var mı? (Şimdilik herkese açık)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Doğrulama kuralları.
     * Hem Store (POST) hem de Update (PUT/PATCH) işlemleri bu kurallardan geçecek.
     */
    public function rules(): array
    {
        // Temel kurallarımız (Her iki işlem için de geçerli)
        $rules = [
            'karnaval_tarihi_baslangic' => ['required', 'date'],
            'karnaval_tarihi_bitis'     => ['required', 'date', 'after_or_equal:karnaval_tarihi_baslangic'],
            'sezon_baslangici'          => ['required', 'date'],
            'karnaval_yili'             => ['required', 'string', 'max:100'],
            'published'                 => ['nullable', 'boolean']
        ];

        /*
         * EĞER İLERİDE FARKLI KURALLAR GEREKİRSE DİYE BİLGİ:
         * İstek türünün PUT veya PATCH (yani güncelleme işlemi) olup olmadığını kontrol edebilirsin.
         * * if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
         * // Sadece güncellemeye özel kuralları buraya ekleyebilir veya değiştirebilirsin
         * // Örneğin: $rules['karnaval_yili'] = ['nullable'];
         * }
         */

        return $rules;
    }
}
