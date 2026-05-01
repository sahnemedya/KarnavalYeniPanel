<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BladeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            abort(403,"Bu işlem için yetkiniz bulunmamaktadır.");
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'blade_file' => 'nullable|file|max:70',
        ];
    }
}
