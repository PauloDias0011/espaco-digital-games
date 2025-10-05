<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Identity\App\Models\Series;

class StudentRegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
            'series_id' => ['required', 'exists:series,id'],
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
            'first_name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'last_name.required' => 'O sobrenome é obrigatório.',
            'last_name.string' => 'O sobrenome deve ser um texto.',
            'last_name.max' => 'O sobrenome não pode ter mais de 255 caracteres.',
            'birthdate.required' => 'A data de nascimento é obrigatória.',
            'birthdate.date' => 'A data de nascimento deve ser uma data válida.',
            'birthdate.before' => 'A data de nascimento deve ser anterior a hoje.',
            'series_id.required' => 'A série é obrigatória.',
            'series_id.exists' => 'A série selecionada não é válida.',
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
            'series_id' => 'série',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verificar se a série pertence ao tenant atual
            if ($this->filled('series_id')) {
                $currentTenant = tenant();
                
                if ($currentTenant) {
                    $series = Series::where('id', $this->series_id)
                        ->where('tenant_id', $currentTenant->id)
                        ->first();

                    if (!$series) {
                        $validator->errors()->add('series_id', 'A série selecionada não pertence a esta unidade.');
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