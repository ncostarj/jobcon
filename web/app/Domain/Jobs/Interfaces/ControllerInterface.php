<?php

namespace App\Domain\Jobs\Interfaces;

use Illuminate\Http\Request;
interface ControllerInterface
{
	public function index(ServiceInterface $service, Request $request);
	// public function create();
	// public function edit(int $id, Request $request);
	// public function store(Request $request);
	// public function update(int $id, Request $request);
	// public function destroy(int $id);
}
