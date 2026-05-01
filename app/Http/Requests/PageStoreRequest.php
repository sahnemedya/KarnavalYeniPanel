<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageStoreRequest extends FormRequest
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
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content_text' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,avif,svg',
            'image2' => 'nullable|mimes:jpg,jpeg,png,webp,avif,svg',
            'icon' => 'nullable|mimes:jpg,jpeg,png,webp,avif,svg',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt',

            'blade_id' => 'nullable',
            'category_id' => 'nullable',
            'translation_of' => 'nullable',
            'lang_id' => 'nullable',
        ];
    }
}
