<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PontoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'usuario_id' => 'required:uuid',
			'tipo' => 'required',
            'dia' => 'required:date',
			'hora' => 'required:time',
			'categoria' => 'required',
			'pedir_ajuste' => 'required',
			'observacao_dia' => 'sometimes',
			'observacao_horario' => 'sometimes',
        ];
    }
}
