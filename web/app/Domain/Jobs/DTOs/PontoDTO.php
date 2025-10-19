<?php

namespace App\Domain\Jobs\DTOs;

use App\Domain\Jobs\Models\Ponto;

class PontoDTO
{
    protected string $user_id;
    protected string $dia;
    protected ?string $categoria;
    protected ?bool $pedir_ajuste;
    protected ?bool $ajuste_finalizado;
    protected ?string $observacao;
    protected array $horario;

    public function __construct(string $user_id, string $dia, ?string $categoria, ?bool $pedir_ajuste, ?bool $ajuste_finalizado, ?string $observacao, array $horario)
    {
        $this->user_id = $user_id;
        $this->dia = $dia;
        $this->categoria = $categoria;
        $this->pedir_ajuste = $pedir_ajuste;
        $this->ajuste_finalizado = $ajuste_finalizado;
        $this->observacao = $observacao;
        $this->horario = $horario;
    }

    public static function fromArray(array $data) : self
    {
        $horarioDTO = HorarioDTO::fromArray($data);
        return new self(
            $data['usuario_id'],
            $data['dia'],
            $data['categoria'],
            $data['pedir_ajuste'],
            $data['ajuste_finalizado']??false,
            $data['observacao_dia'],
            $horarioDTO->toArray()
        );
    }


    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'dia' => $this->dia,
            'categoria' => $this->categoria,
            'pedir_ajuste' => $this->pedir_ajuste,
            'ajuste_finalizado' => $this->ajuste_finalizado,
            'observacao' => $this->observacao,
            'horario' => $this->horario
        ];
    }
}
