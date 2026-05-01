<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'note' => 'nullable|string|max:100',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,avif,svg|max:2048',
            'show_menu' => 'nullable|boolean',
            'show_homepage' => 'nullable|boolean',
            'show_footer' => 'nullable|boolean',
            'hit' => 'nullable|integer|min:0|max:9999',
            'parent_category' => 'nullable|integer',
            'translation_of' =>'nullable|integer',
            'lang_id' => 'nullable|integer'
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'Kategori adı zorunludur.',
            'name.string' => 'Kategori adı metin olmalıdır.',
            'name.max' => 'Kategori adı en fazla 100 karakter olabilir.',

            'note.string' => 'Not metin olmalıdır.',
            'note.max' => 'Not en fazla 100 karakter olabilir.',

            'image.mimes' => 'Resim türü jpg,jpeg,png,webp,avif,svg olabilir.',
            'image.max' => 'Resim boyutu en fazla 2048KB (2MB) olabilir.',

            'show_menu.boolean' => 'Menüde göster alanı sadece true veya false olabilir.',
            'show_homepage.boolean' => 'Anasayfada göster alanı sadece true veya false olabilir.',
            'show_footer.boolean' => 'Alt menüde göster alanı sadece true veya false olabilir.',

            'hit.integer' => 'Hit alanı tam sayı olmalıdır.',
            'hit.min' => 'Hit değeri en az 0 olmalıdır.',
            'hit.max' => 'Hit değeri en fazla 9999 olabilir.',

            'parent_category.integer' => 'Üst kategori ID’si tam sayı olmalıdır.',
            'parent_category.exists' => 'Seçilen üst kategori geçersiz veya silinmiş olabilir.',

            'translation_of.integer' => 'Çeviri kaynağı ID’si tam sayı olmalıdır.',
            'translation_of.exists' => 'Seçilen çeviri kaynağı geçersiz veya silinmiş olabilir.',

            'lang_id.integer' => 'Dil ID’si tam sayı olmalıdır.',
        ];
    }
}
