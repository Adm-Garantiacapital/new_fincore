<?php

namespace App\Http\Requests\Proveedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreProveedorRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'ruc'                   => 'required|string|size:11|unique:proveedores,ruc',
            'pagina_web'            => 'nullable|url|max:255',
            'razon_social'          => 'required|string|max:255|unique:proveedores,razon_social',
            'nombre_comercial'      => 'nullable|string|max:255',
            'fecha_inicio_actividad'=> 'nullable|date',
            'actividad_economica'   => 'nullable|string|max:255',
            'direccion'             => 'nullable|string|max:500',
            'ventas_aproximadas'    => 'nullable|numeric|min:0',
            'persona_id'            => 'nullable|exists:personas,id',
        ];
    }
    public function messages(): array{
        return [
            'ruc.required'      => 'El RUC es obligatorio.',
            'ruc.string'        => 'El RUC debe ser una cadena de texto.',
            'ruc.size'          => 'El RUC debe tener exactamente 11 caracteres.',
            'ruc.unique'        => 'Ya existe un proveedor con este RUC.',

            'pagina_web.url'    => 'La página web debe ser una URL válida.',
            'pagina_web.max'    => 'La página web no debe superar los 255 caracteres.',

            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.string'   => 'La razón social debe ser una cadena de texto.',
            'razon_social.max'      => 'La razón social no debe superar los 255 caracteres.',
            'razon_social.unique'   => 'Ya existe un proveedor con esta razón social.',

            'nombre_comercial.string' => 'El nombre comercial debe ser una cadena de texto.',
            'nombre_comercial.max'    => 'El nombre comercial no debe superar los 255 caracteres.',

            'fecha_inicio_actividad.date' => 'La fecha de inicio de actividad debe ser una fecha válida.',

            'actividad_economica.string' => 'La actividad económica debe ser una cadena de texto.',
            'actividad_economica.max'    => 'La actividad económica no debe superar los 255 caracteres.',

            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max'    => 'La dirección no debe superar los 500 caracteres.',

            'ventas_aproximadas.numeric' => 'Las ventas aproximadas deben ser un valor numérico.',
            'ventas_aproximadas.min'     => 'Las ventas aproximadas no pueden ser negativas.',

            'persona_id.exists'   => 'La persona seleccionada no existe en el sistema.',
        ];
    }
}
