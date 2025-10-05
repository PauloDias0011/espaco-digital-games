<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Identity\App\Models\User;

class StudentLoginRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'O nome é obrigatório.',
            'first_name.string' => 'O nome deve ser um texto.',
            'last_name.required' => 'O sobrenome é obrigatório.',
            'last_name.string' => 'O sobrenome deve ser um texto.',
            'birthdate.required' => 'A data de nascimento é obrigatória.',
            'birthdate.date' => 'A data de nascimento deve ser uma data válida.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'nome',
            'last_name' => 'sobrenome',
            'birthdate' => 'data de nascimento',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verificar se as credenciais são válidas
            if ($this->filled(['first_name', 'last_name', 'birthdate'])) {
                $currentTenant = tenant();
                
                if ($currentTenant) {
                    $user = User::findByStudentCredentials(
                        $this->first_name,
                        $this->last_name,
                        $this->birthdate,
                        $currentTenant->id
                    );

                    if (!$user) {
                        $validator->errors()->add('first_name', 'Credenciais inválidas. Verifique seus dados.');
                    }
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim($this->first_name),
            'last_name' => trim($this->last_name),
        ]);
    }
}