<?php

namespace App\Domain\Jobs\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ServiceInterface {
    public function search(array $criteria): Collection;
    public function find(string $id): ?Model;
    public function create(array $data): Model;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
}
