<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AramaKayitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'arama' => 'required|in:wp,tel'
        ];
    }

    public function messages(): array
    {
        return [
            'arama.required' => 'Arama türü gereklidir',
            'arama.in' => 'Geçersiz arama türü'
        ];
    }
}
