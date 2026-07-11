<?php

namespace App\Domain\Jobs\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ServiceInterface {
    public function search(Request $request): Collection;
    public function find(string $id): ?Model;
    public function create(Request $request): Model;
    public function update(string $id, Request $request): bool;
    public function delete(string $id): bool;
}
