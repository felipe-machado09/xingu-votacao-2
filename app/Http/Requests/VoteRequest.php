<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class VoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return session()->has('audience_id');
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $category = $this->route('category');
            $company = $this->route('company');

            if (!$category->isOpen()) {
                $validator->errors()->add('category', 'Esta categoria não está aberta para votação.');
            }

            if (!$category->companies()->where('companies.id', $company->id)->exists()) {
                $validator->errors()->add('company', 'Esta empresa não participa desta categoria.');
            }

            $audienceId = session('audience_id');
            $audience = \App\Models\Audience::find($audienceId);

            if ($audience && $audience->hasVotedInCategory($category->id)) {
                $validator->errors()->add('vote', 'Você já votou nesta categoria.');
            }
        });
    }
}
