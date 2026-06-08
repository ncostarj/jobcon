<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Contracts\RepositoryInterface;
use App\Domain\Jobs\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmpresaRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(Empresa $empresa)
	{
		$this->model = $empresa;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
			->where('user_id', $criteria['usuario_id'])
			->when($criteria['razao_social']??false, fn($query, $razao_social) => $query->where('razao_social', $razao_social))
			->when($criteria['estabelecimento']??false, fn($query, $razao_social) => $query->where('razao_social', $razao_social))
			->when($criteria['cnpj']??false, fn($query, $razao_social) => $query->where('razao_social', $razao_social))
			->limit($criteria['limite'] ?? 5)
			->orderBy('razao_social', 'asc')
			->get();
	}

	public function find(string $id): ?Empresa
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): Empresa
	{
		return $this->model->create($data);
	}

	public function update(string $id, array $data): bool
	{
		$empresa = $this->find($id);
		return $empresa->update($data);
	}

	public function delete(string $id): bool
	{
		$empresa = $this->find($id);
		return $empresa->delete();
	}
}
