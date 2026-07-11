<?php

namespace App\Domain\Jobs\Repositories;

use App\Domain\Jobs\Contracts\RepositoryInterface;
use App\Domain\Jobs\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Collection;

class UsuarioRepository implements RepositoryInterface
{
	protected $model;

	public function __construct(User $user)
	{
		$this->model = $user;
	}

	public function search(array $criteria): Collection
	{
		return $this->model
			->when($criteria['email'] ?? false, fn($query, $email) => $query->where('email', $email))
			->get();
	}

	public function find(string $id): ?User
	{
		return $this->model->findOrFail($id);
	}

	public function create(array $data): User
	{
		return $this->model->create($data);
	}

	public function createForPonto($ponto, array $data) {
		return $ponto->pontos()->create($data);
	}

	public function update(string $id, array $data): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->update($data);
	}

	public function delete(string $id): bool
	{
		$contracheque = $this->find($id);
		return $contracheque->delete();
	}
}
