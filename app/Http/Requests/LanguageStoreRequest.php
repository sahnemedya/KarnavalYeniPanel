<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LanguageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//        İzin gerekiyorsa bilgisi buraya eklenir

        if (auth()->check()) {
            return true;
        }
        abort(403, "Bu işlem için yetkiniz bulunmamaktadır.");

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:50"],
            "code" => ["required", "string", "max:3"],
            "active" => ["boolean"],
        ];
    }
}
