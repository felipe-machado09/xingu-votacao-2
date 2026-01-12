<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCNPJ implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', $value);
        
        // Verificar se tem 14 dígitos
        if (strlen($cnpj) != 14) {
            $fail('O CNPJ deve ter 14 dígitos.');
            return;
        }
        
        // Verificar se todos os dígitos são iguais (ex: 11111111111111)
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail('O CNPJ informado é inválido.');
            return;
        }
        
        // Validar dígitos verificadores
        if (!$this->validateCNPJ($cnpj)) {
            $fail('O CNPJ informado é inválido. Verifique se os dígitos verificadores estão corretos.');
        }
    }
    
    /**
     * Valida CNPJ usando algoritmo de validação
     */
    private function validateCNPJ(string $cnpj): bool
    {
        // Calcular primeiro dígito verificador
        $length = 12;
        $sum = 0;
        $pos = 5;
        
        for ($i = 0; $i < $length; $i++) {
            $sum += (int)$cnpj[$i] * $pos;
            $pos = ($pos == 2) ? 9 : $pos - 1;
        }
        
        $result = $sum % 11;
        $digit1 = ($result < 2) ? 0 : 11 - $result;
        
        if ((int)$cnpj[12] != $digit1) {
            return false;
        }
        
        // Calcular segundo dígito verificador
        $length = 13;
        $sum = 0;
        $pos = 6;
        
        for ($i = 0; $i < $length; $i++) {
            $sum += (int)$cnpj[$i] * $pos;
            $pos = ($pos == 2) ? 9 : $pos - 1;
        }
        
        $result = $sum % 11;
        $digit2 = ($result < 2) ? 0 : 11 - $result;
        
        return (int)$cnpj[13] == $digit2;
    }
}
