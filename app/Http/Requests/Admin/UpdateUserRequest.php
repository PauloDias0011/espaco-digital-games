<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Identity\App\Models\Classroom;
use Modules\Identity\App\Models\Series;
use Modules\Identity\App\Models\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization será feita no controller
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
            'class_id' => ['nullable', 'exists:classes,id'],
            'status' => ['required', 'in:active,suspended'],
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
            'class_id.exists' => 'A turma selecionada não é válida.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser ativo ou suspenso.',
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
            'class_id' => 'turma',
            'status' => 'status',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $currentTenant = tenant();
            
            if ($currentTenant) {
                // Verificar se a série pertence ao tenant atual
                if ($this->filled('series_id')) {
                    $series = Series::where('id', $this->series_id)
                        ->where('tenant_id', $currentTenant->id)
                        ->first();

                    if (!$series) {
                        $validator->errors()->add('series_id', 'A série selecionada não pertence a esta unidade.');
                    }
                }

                // Verificar se a turma pertence ao tenant atual
                if ($this->filled('class_id')) {
                    $classroom = Classroom::where('id', $this->class_id)
                        ->where('tenant_id', $currentTenant->id)
                        ->first();

                    if (!$classroom) {
                        $validator->errors()->add('class_id', 'A turma selecionada não pertence a esta unidade.');
                    }
                }

                // Verificar se já existe outro aluno com os mesmos dados (excluindo o atual)
                if ($this->filled(['first_name', 'last_name', 'birthdate'])) {
                    $existingStudent = User::where('first_name', $this->first_name)
                        ->where('last_name', $this->last_name)
                        ->where('birthdate', $this->birthdate)
                        ->where('tenant_id', $currentTenant->id)
                        ->where('role', 'aluno')
                        ->where('id', '!=', $this->route('user')->id)
                        ->first();

                    if ($existingStudent) {
                        $validator->errors()->add('first_name', 'Já existe outro aluno cadastrado com estes dados.');
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
