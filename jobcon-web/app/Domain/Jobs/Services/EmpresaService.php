<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\DTOs\HorarioDTO;
use App\Domain\Jobs\DTOs\PontoDTO;
use App\Domain\Jobs\Contracts\ServiceInterface;
use App\Domain\Jobs\Models\Ponto;
use App\Domain\Jobs\Repositories\EmpresaRepository;
use App\Domain\Jobs\Repositories\HorarioRepository;
use App\Domain\Jobs\Repositories\PontoRepository;
use App\Domain\Jobs\Repositories\UsuarioRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmpresaService implements ServiceInterface
{
	// TODO readequar o empresa service
	protected $empresaRepository;
	// protected $bancoRepository;

	public function __construct(EmpresaRepository $empresaRepository, HorarioRepository $horarioRepository, UsuarioRepository $usuarioRepository)
	{
		$this->empresaRepository = $empresaRepository;
	}

	public function search(array $criteria = []): Collection
	{
		return $this->empresaRepository->search($criteria);
	}

	public function find(string $id): ?Ponto
	{
		return $this->empresaRepository->find($id);
	}

	public function create(array $data): Ponto
	{
		return $this->empresaRepository->create($data);
	}

	public function update(string $id, array $data): bool
	{
		return $this->empresaRepository->update($id, $data);
	}

	public function delete(string $id): bool
	{
		return $this->empresaRepository->delete($id);
	}
}
