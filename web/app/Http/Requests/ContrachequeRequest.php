<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContrachequeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa_id' => 'required|uuid',
			'competencia' => 'required',
			'tipo' => 'required',
			'salario_base' => 'required|numeric',
			'salario_liquido' => 'required|numeric',
			'total_vencimentos' => 'required|numeric',
			'total_descontos' => 'required|numeric',
        ];
    }
}
