<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // Importar la interfaz Validator
use Illuminate\Http\Exceptions\HttpResponseException;  // Importar la excepción HttpResponseException

class UpdateCitasRequest extends FormRequest
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
        return [
            'empleado_id' => 'sometimes|exists:empleados,id', // El empleado_id es opcional y debe existir en la tabla empleados
            //'cliente_id' => 'sometimes|exists:clientes,id', // El cliente_id es opcional y debe exsistir en la tabla clientes
            'fecha_hora' => 'sometimes|date', // La fecha_hora es opcional y debe ser una fecha valida
            'servicios' => 'sometimes|array', // Los son servicios son opcionales y deben ser un array
            'servicios' => 'sometimes', // Los servicios deben existir en la tabla servicios
        ];
    }
    
    // Manejar la falla de validación y devolver una respuesta JSON personalizada
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación en la actualización',
            'errors' => $validator->errors()
        ], 422));
    }
}
