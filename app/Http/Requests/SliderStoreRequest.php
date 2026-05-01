<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderStoreRequest extends FormRequest
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
            'title' => "required|string|max:100",
            'description' => "nullable|string",
            'image' => "nullable|image|max:3052|mimes:jpg,jpeg,png,avif,webp",
            'mobile_image' => "nullable|image|max:3052|mimes:jpg,jpeg,png,avif,webp",
            'url' => "nullable|max:150",
            'hit' => "nullable",
            'lang_id' => "nullable|exists:languages,id",
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Başlık alanı zorunludur.',
            'title.string' => 'Başlık metin türünde olmalıdır.',
            'title.max' => 'Başlık en fazla 100 karakter olabilir.',

            'description.string' => 'Açıklama metin türünde olmalıdır.',

            'image.image' => 'Yüklenen dosya bir resim olmalıdır.',
            'image.max' => 'Resim en fazla 3MB (3052 KB) boyutunda olmalıdır.',
            'image.mimes' => 'Resim yalnızca jpg, jpeg, png, avif veya webp formatında olmalıdır.',

            'mobile_image.image' => 'Mobil resim dosyası bir resim olmalıdır.',
            'mobile_image.max' => 'Mobil resim en fazla 3MB (3052 KB) boyutunda olmalıdır.',
            'mobile_image.mimes' => 'Mobil resim yalnızca jpg, jpeg, png, avif veya webp formatında olmalıdır.',

            'url.max' => 'URL en fazla 150 karakter olabilir.',

            'lang_id.exists' => 'Seçilen dil geçerli değil.',
        ];
    }
}
