<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
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
        $tenantId = $this->route('tenant')->id ?? $this->route('tenant');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($tenantId) {
                    $exists = \Modules\Tenancy\App\Models\Tenant::all()->contains(function ($tenant) use ($value, $tenantId) {
                        return $tenant->id !== $tenantId && strtolower($tenant->getName()) === strtolower($value);
                    });
                    
                    if ($exists) {
                        $fail('Já existe uma unidade com este nome.');
                    }
                },
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                function ($attribute, $value, $fail) use ($tenantId) {
                    if (empty($value)) {
                        return;
                    }
                    
                    $exists = \Modules\Tenancy\App\Models\Tenant::all()->contains(function ($tenant) use ($value, $tenantId) {
                        return $tenant->id !== $tenantId && strtolower($tenant->getSlug()) === strtolower($value);
                    });
                    
                    if ($exists) {
                        $fail('Já existe uma unidade com este slug.');
                    }
                },
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['active', 'inactive']),
            ],
            'settings' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nome da Unidade',
            'slug' => 'Slug',
            'status' => 'Status',
            'settings' => 'Configurações',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da unidade é obrigatório.',
            'name.unique' => 'Já existe uma unidade com este nome.',
            'slug.unique' => 'Já existe uma unidade com este slug.',
            'slug.regex' => 'O slug deve conter apenas letras minúsculas, números e hífens.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser ativo ou inativo.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('name') && !$this->has('slug')) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }
}
