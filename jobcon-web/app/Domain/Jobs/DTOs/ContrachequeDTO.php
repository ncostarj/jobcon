<?php

namespace App\Domain\Jobs\DTOs;

use Illuminate\Http\Request;

class ContrachequeDTO
{
    // TODO mudar user_id para objeto de usuario
    protected string $user_id;
    protected string $empresa_id;
    protected string $competencia;
    protected float $salario_base;
    protected float $salario_liquido;
    protected float $total_vencimentos;
    protected float $total_descontos;

    public function __construct(string $user_id, string $empresa_id, string $competencia, float $salario_base, float $salario_liquido, float $total_vencimentos, float $total_descontos)
    {
        $this->user_id = $user_id;
        $this->empresa_id = $empresa_id;
        $this->competencia = $competencia;
        $this->salario_base = $salario_base;
        $this->salario_liquido = $salario_liquido;
        $this->total_vencimentos = $total_vencimentos;
        $this->total_descontos = $total_descontos;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['usuario_id'],
            $data['empresa_id'],
            $data['competencia'],
            $data['salario_base'],
            $data['salario_liquido'],
            $data['total_vencimentos'],
            $data['total_descontos']
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('usuario_id'),
            $request->input('empresa_id'),
            $request->input('competencia'),
            $request->input('salario_base'),
            $request->input('salario_liquido'),
            $request->input('total_vencimentos'),
            $request->input('total_descontos')
        );
    }

    public function toArray(): array
    {
        return [
           'user_id' => $this->user_id,
           'empresa_id' => $this->empresa_id,
           'competencia' => $this->competencia,
           'salario_base' => $this->salario_base,
           'salario_liquido' => $this->salario_liquido,
           'total_vencimentos' => $this->total_vencimentos,
           'total_desconto' => $this->total_descontos
        ];
    }
}
