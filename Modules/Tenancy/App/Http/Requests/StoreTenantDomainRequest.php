<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantDomainRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'domain' => [
                'required',
                'string',
                'max:255',
                'unique:tenant_domains,domain',
                'regex:/^[a-zA-Z0-9.-]+$/',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'domain' => 'Domínio',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'domain.required' => 'O domínio é obrigatório.',
            'domain.unique' => 'Este domínio já está em uso.',
            'domain.regex' => 'O domínio deve conter apenas letras, números, pontos e hífens.',
        ];
    }
}
