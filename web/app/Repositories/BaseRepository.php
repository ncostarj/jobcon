<?php

namespace App\Repositories;

abstract class BaseRepository
{
	private $model;

	public function __construct($model) {
		$this->model = $model::class;
	}

	public abstract function get(array $data = []);

	public function insert(array $data)
	{
		return $this->model::create($data);
	}

	public function update(string $id, array $data) {
		return $this->model::where('id', $id)
			->fill($data)
			->update($data);
	}

	public function delete($id) {
		return $this->model::where('id', $id)
			->delete();
	}

}
