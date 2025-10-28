<?php

namespace App\Domain\Jobs\DTOs;

use App\Domain\Jobs\Models\Ponto;
use Illuminate\Http\Request;

class HorarioDTO
{
	protected ?Ponto $ponto;
	protected string $hora;
	protected string $tipo;
	protected ?string $observacao;

    public function __construct(?Ponto $ponto, string $hora, string $tipo, ?string $observacao)
    {
		$this->ponto = $ponto;
        $this->hora = $hora;
        $this->tipo = $tipo;
        $this->observacao = $observacao;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
			$data['ponto'],
            $data['hora'],
            $data['tipo'],
            $data['observacao'],
        );
    }

	public static function fromRequest(Request $request) {
        return new self(
			$request->input('ponto'),
            $request->input('hora'),
            $request->input('tipo'),
            $request->input('observacao'),
        );
	}

    public function toArray(): array
    {
        return [
			'ponto' => $this->ponto,
            'hora' => $this->hora,
            'tipo' => $this->tipo,
            'observacao' => $this->observacao??'',
        ];
    }
}
