<?php

namespace App\Domain\Jobs\DTOs;

use App\Models\User;
use Illuminate\Http\Request;

class PontoDTO
{
	protected User $usuario;
	protected string $dia;
	protected string $categoria;
	protected bool $pedir_ajuste;
	protected ?bool $ajuste_finalizado;
	protected ?string $observacao;

	public function __construct(User $usuario, string $dia, string $categoria, bool $pedir_ajuste, ?bool $ajuste_finalizado, ?string $observacao)
	{

		$this->usuario = $usuario;
		$this->dia = $dia;
		$this->categoria = $categoria;
		$this->pedir_ajuste = $pedir_ajuste;
		$this->ajuste_finalizado = $ajuste_finalizado;
		$this->observacao = $observacao;
	}

	public static function fromRequest(Request $request)
	{
		return new self(
			$request->input('usuario'),
			$request->input('dia'),
			$request->input('categoria'),
			$request->input('pedir_ajuste'),
			$request->input('ajuste_finalizado'),
			$request->input('observacao')
		);
	}

	public function toArray(): array
	{
		return [
			'usuario' => $this->usuario,
			'dia' => $this->dia,
			'categoria' => $this->categoria,
			'pedir_ajuste' => $this->pedir_ajuste,
			'ajuste_finalizado' => $this->ajuste_finalizado??false,
			'observacao' => $this->observacao??'',
		];
	}
}
