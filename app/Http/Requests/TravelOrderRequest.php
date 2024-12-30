<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelOrderRequest extends FormRequest
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
        $rules = [];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['status'] = 'required|in:solicitado,aprovado,cancelado';
            return $rules;
        }
        
        $rules = [
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];

        return $rules;
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            
            'destination.required' => 'O destino é obrigatório.',
            'destination.string' => 'O destino deve ser uma string.',
            'destination.max' => 'O destino não pode ter mais de 255 caracteres.',
            
            'start_date.required' => 'A data de início é obrigatória.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            
            'end_date.required' => 'A data de término é obrigatória.',
            'end_date.date' => 'A data de término deve ser uma data válida.',
            'end_date.after' => 'A data de término deve ser posterior à data de início.',
            
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser um dos seguintes valores: solicitado, aprovado, cancelado.',
        ];
    }
}
