<?php

namespace App\Domain\Jobs\DTOs;

use App\Domain\Jobs\Models\Ponto;
use Illuminate\Http\Request;

class HorarioDTO
{
	protected string $hora;
	protected string $tipo;
	protected ?string $observacao;

    public function __construct(string $hora, string $tipo, ?string $observacao)
    {
        $this->hora = $hora;
        $this->tipo = $tipo;
        $this->observacao = $observacao;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['hora'],
            $data['tipo'],
            $data['observacao'],
        );
    }

	public static function fromRequest(Request $request) {

	}

    public function toArray(): array
    {
        return [
            'hora' => $this->hora,
            'tipo' => $this->tipo,
            'observacao' => $this->observacao??'',
        ];
    }
}
