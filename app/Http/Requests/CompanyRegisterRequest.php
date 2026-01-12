<?php

namespace App\Http\Requests;

use App\Rules\ValidCNPJ;
use App\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Limpar CNPJ para validação única (apenas dígitos)
        $cnpjClean = preg_replace('/\D/', '', $this->cnpj ?? '');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => [
                'required', 
                'string', 
                new ValidCNPJ, 
                function ($attribute, $value, $fail) use ($cnpjClean) {
                    $exists = \App\Models\Company::where('cnpj', $cnpjClean)->exists();
                    if ($exists) {
                        $fail('Este CNPJ já está cadastrado.');
                    }
                }
            ],
            'telefone' => ['required', 'string', new ValidPhone],
            'whatsapp_number' => ['nullable', 'string', new ValidPhone],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'address_street' => ['required', 'string', 'max:255'],
            'address_number' => ['required', 'string', 'max:20'],
            'address_complement' => ['nullable', 'string', 'max:255'],
            'address_neighborhood' => ['required', 'string', 'max:255'],
            'address_city' => ['required', 'string', 'max:255'],
            'address_state' => ['required', 'string', 'size:2'],
            'address_zipcode' => ['required', 'string', 'regex:/^\d{5}-?\d{3}$/'],
            'logo_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'categoria_id' => ['required', 'array', 'min:1'],
            'categoria_id.*' => ['required', 'exists:categories,id'],
            'lgpd' => ['required', 'accepted'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da empresa é obrigatório.',
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'address_street.required' => 'A rua é obrigatória.',
            'address_number.required' => 'O número é obrigatório.',
            'address_neighborhood.required' => 'O bairro é obrigatório.',
            'address_city.required' => 'A cidade é obrigatória.',
            'address_state.required' => 'O estado é obrigatório.',
            'address_state.size' => 'O estado deve ter 2 caracteres (ex: PA).',
            'address_zipcode.required' => 'O CEP é obrigatório.',
            'address_zipcode.regex' => 'O CEP deve ter 8 dígitos no formato 00000-000.',
            'categoria_id.required' => 'Selecione pelo menos uma categoria.',
            'categoria_id.min' => 'Selecione pelo menos uma categoria.',
            'lgpd.required' => 'Você deve aceitar os termos de privacidade.',
            'lgpd.accepted' => 'Você deve aceitar os termos de privacidade.',
        ];
    }
}
