<?php

namespace App\Domain\Jobs\DTOs;

use App\Domain\Jobs\Models\Ponto;

class HorarioDTO
{
    protected string $dia;
	protected string $hora;
	protected string $tipo;
	protected ?string $observacao;

    public function __construct(string $dia, string $hora, string $tipo, ?string $observacao)
    {
        $this->dia = $dia;
        $this->hora = $hora;
        $this->tipo = $tipo;
        $this->observacao = $observacao;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['dia'],
            $data['hora'],
            $data['tipo'],
            $data['observacao_horario'],
        );
    }


    public function toArray(): array
    {
        return [
            'dia' => $this->dia,
            'hora' => $this->hora,
            'tipo' => $this->tipo,
            'observacao' => $this->observacao,
        ];
    }
}
