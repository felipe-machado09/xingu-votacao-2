<?php

namespace App\Http\Requests;

use App\Rules\ValidCNPJ;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $cnpj = $this->input('cnpj');
        $whatsapp = $this->input('whatsapp_number');
        $this->merge([
            'cnpj' => is_string($cnpj) ? preg_replace('/\D/', '', $cnpj) : $cnpj,
            'whatsapp_number' => is_string($whatsapp) ? preg_replace('/\D/', '', $whatsapp) : $whatsapp,
        ]);
    }

    public function rules(): array
    {
        return [
            'trade_name' => ['required', 'string', 'max:255'],
            'cnpj' => ['bail', 'required', 'string', new ValidCNPJ, 'unique:companies,cnpj'],
            'whatsapp_number' => ['bail', 'required', 'string', new ValidPhone],
            'instagram_url' => ['nullable', 'url:http,https', 'max:255'],
            'logo_path' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'categoria_id' => ['required', 'array', 'min:1'],
            'categoria_id.*' => ['required', 'integer', 'distinct', Rule::exists('categories', 'id')->where('is_active', true)],
            'lgpd' => ['required', 'accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'trade_name' => 'nome fantasia',
            'whatsapp_number' => 'WhatsApp',
            'logo_path' => 'logomarca',
            'instagram_url' => 'link do Instagram',
            'categoria_id' => 'categorias',
            'lgpd' => 'aceite dos termos de privacidade',
        ];
    }
}
