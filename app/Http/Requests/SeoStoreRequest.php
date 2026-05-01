<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            abort(403, "Bu işlem için yetkiniz bulunmamaktadır.");
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'canonical' => 'integer|min:1|exists:pages,id|nullable',
            'page_id' => 'required|integer|min:1|exists:pages,id|nullable',

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Başlık alanı zorunludur.',
            'title.string' => 'Başlık metin biçiminde olmalıdır.',
            'title.max' => 'Başlık en fazla 255 karakter olabilir.',

            'description.required' => 'Açıklama alanı zorunludur.',
            'description.string' => 'Açıklama metin biçiminde olmalıdır.',
            'description.max' => 'Açıklama en fazla 255 karakter olabilir.',

            'canonical.integer' => 'Canonical değeri bir tam sayı olmalıdır.',
            'canonical.min' => 'Canonical değeri en az 1 olmalıdır.',
            'canonical.exists' => 'Seçilen canonical sayfa bulunamadı.',

            'page_id.required' => 'Sayfa ID alanı zorunludur.',
            'page_id.integer' => 'Sayfa ID değeri bir tam sayı olmalıdır.',
            'page_id.min' => 'Sayfa ID en az 1 olmalıdır.',
            'page_id.exists' => 'Seçilen sayfa ID sistemde bulunamadı.',
        ];
    }
}
