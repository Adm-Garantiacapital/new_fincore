<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteCarnetRequests extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return [
            'sales_executive' => ['nullable'],
            'nombre' => ['nullable'],
            'address' => ['nullable'],
            'fecha_nacimiento' => ['nullable'],
            'sexo' => ['nullable'],
            'estado_civil' => ['nullable'],
            'expected_rate' => ['nullable'],
            'commission' => ['nullable'],
            'email' => ['nullable'],
            'numero_movil' => ['nullable'],
        ];
    }
}
