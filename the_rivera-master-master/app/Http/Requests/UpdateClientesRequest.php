<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // Importar la interfaz Validator
use Illuminate\Http\Exceptions\HttpResponseException;  // Importar la excepción HttpResponseException

class UpdateClientesRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255', // El nombre es opcional, debe ser una cadena y no exceder 255 caracteres
            'email' => 'sometimes|email|unique:clientes,email,' . $this->route('cliente')->id, // El email es opcional, debe ser un email valido y unico en la tabla clientes, ignorando el cliente actual
            'telefono' => 'sometimes|string|max:20', // El telefono es opcional, debe ser una cadena y no exceder 20 caracteres
            'password' => 'sometimes|string|max:500', // La direccion es opcional, debe ser una cadena y no exceder 500 caracteres
            //'empleado_id' => 'required|exists:empleados,id', // El empleado_id es obligatorio y debe existir en la tabla empleados
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
