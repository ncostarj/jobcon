<?php

namespace App\Http\Requests;

use Http\Requests\BaseRequest;

class PontoRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dia' => 'required:date',
			'categoria' => 'required',
			'pedir_ajuste' => 'required',
        ];
    }
}
