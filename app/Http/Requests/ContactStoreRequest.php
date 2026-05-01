<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            abort(403,"Bu işlem için yetkiniz bulunmamaktadır.");
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
            'name'      => 'required|string|max:100',
            'email'     => 'nullable|email|max:100',
            'email2'    => 'nullable|email|max:100',
            'phone'     => 'nullable|string|max:20',
            'phone2'    => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'country'   => 'nullable|string|max:50',
            'city'      => 'nullable|string|max:50',
            'state'     => 'nullable|string|max:50',
            'person'    => 'nullable|string|max:75',
            'map'       => 'nullable|string|max:750',
            'hit'       => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'İsim alanı zorunludur.',
            'name.string'        => 'İsim alanı metin olmalıdır.',
            'name.max'           => 'İsim en fazla 100 karakter olabilir.',

            'email.email'        => 'Geçerli bir e-posta adresi giriniz.',
            'email.max'          => 'E-posta en fazla 100 karakter olabilir.',

            'email2.email'       => 'İkinci e-posta geçerli bir e-posta adresi olmalıdır.',
            'email2.max'         => 'İkinci e-posta en fazla 100 karakter olabilir.',

            'phone.string'       => 'Telefon numarası metin olmalıdır.',
            'phone.max'          => 'Telefon numarası en fazla 20 karakter olabilir.',

            'phone2.string'      => 'İkinci telefon numarası metin olmalıdır.',
            'phone2.max'         => 'İkinci telefon numarası en fazla 20 karakter olabilir.',

            'address.string'     => 'Adres metin olmalıdır.',
            'address.max'        => 'Adres en fazla 255 karakter olabilir.',

            'country.string'     => 'Ülke adı metin olmalıdır.',
            'country.max'        => 'Ülke adı en fazla 50 karakter olabilir.',

            'city.string'        => 'Şehir adı metin olmalıdır.',
            'city.max'           => 'Şehir adı en fazla 50 karakter olabilir.',

            'state.string'       => 'Eyalet/ilçe adı metin olmalıdır.',
            'state.max'          => 'Eyalet/ilçe adı en fazla 50 karakter olabilir.',

            'person.string'      => 'Yetkili kişi adı metin olmalıdır.',
            'person.max'         => 'Yetkili kişi adı en fazla 75 karakter olabilir.',

            'map.string'         => 'Harita bağlantısı metin olmalıdır.',
            'map.max'            => 'Harita bağlantısı en fazla 750 karakter olabilir.',

            'hit.integer'        => 'Gösterim sırası bir tam sayı olmalıdır.',
            'hit.min'            => 'Gösterim sırası 0\'dan küçük olamaz.',

        ];
    }
}
