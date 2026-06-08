<?php

namespace App\Domain\Jobs\DTOs;

use App\Models\User;
use Illuminate\Http\Request;

class FeriasDTO
{
	protected ?User $usuario;
	protected string $inicio;
	protected string $fim;
	protected ?string $qtd_dias;
	protected ?string $observacao;

    public function __construct(?User $usuario, string $inicio, string $fim, string $qtd_dias, ?string $observacao)
    {
		$this->usuario = $usuario;
        $this->inicio = $inicio;
        $this->fim = $fim;
        $this->qtd_dias = $qtd_dias;
        $this->observacao = $observacao;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
			$data['usuario'],
            $data['inicio'],
            $data['fim'],
            $data['qtd_diass'],
            $data['observacao'],
        );
    }

	public static function fromRequest(Request $request) {
        return new self(
			$request->input('usuario'),
            $request->input('inicio'),
            $request->input('fim'),
            $request->input('qtd_dias'),
            $request->input('observacao'),
        );
	}

    public function toArray(): array
    {
        return [
			'usuario' => $this->usuario,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
            'qtd_dias' => $this->qtd_dias,
            'observacao' => $this->observacao??'',
        ];
    }
}
