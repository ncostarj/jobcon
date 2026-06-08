<?php

namespace App\Domain\Jobs\Contracts;

use Illuminate\Http\Request;
interface ControllerInterface
{
	public function index(Request $request);
	public function create();
	public function edit(string $id);
	public function store(Request $request);
	public function update(string $id, Request $request);
	public function destroy(string $id);
}
