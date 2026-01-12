<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = preg_replace('/\D/', '', $value);
        
        // Verificar se tem 11 dígitos
        if (strlen($phone) != 11) {
            $fail('O telefone deve ter 11 dígitos (DDD + número com 9 dígitos).');
            return;
        }
        
        // Verificar se todos os dígitos são iguais (ex: 11111111111)
        if (preg_match('/(\d)\1{10}/', $phone)) {
            $fail('O telefone informado é inválido.');
            return;
        }
        
        // Verificar se o DDD é válido (11-99)
        $ddd = substr($phone, 0, 2);
        if ($ddd < 11 || $ddd > 99) {
            $fail('O DDD informado é inválido.');
            return;
        }
        
        // Verificar se o número começa com 9 (celular) ou 2-8 (fixo)
        $firstDigit = (int)$phone[2];
        if ($firstDigit < 2 || $firstDigit > 9) {
            $fail('O número de telefone informado é inválido.');
        }
    }
}
