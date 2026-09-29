<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;  // Importar la interfaz Validator 
use Illuminate\Http\Exceptions\HttpResponseException;  // Importar la excepción HttpResponseException 

class StoreEmpleadosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir que cualquier usuario pueda hacer esta request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // El nombre es obligatorio, debe ser una cadena y no exceder los 255 caracteres
            'email' => 'required|email|unique:empleados,email', // El email es obligatorio, debe ser un email valido y unico en la tabla empleados
            'telefono' => 'nullable|string|max:20', // El telefono es opcional, debe ser una cadena y no exceder los 20 caracteres
            'password' => 'required|string|min:8', // El password es obligatorio, debe ser una cadena, tener al menos 8 caracteres y debe coincidir con el campo password_confirmation
            
        ];
    }
    // Manejar la falla de validación y devolver una respuesta JSON personalizada
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }
}
