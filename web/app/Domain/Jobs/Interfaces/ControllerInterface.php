<?php

namespace App\Domain\Jobs\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ControllerInterface
{

	public function index(Request $request);
	public function create();
	public function edit(Model $model, Request $request);
	public function store(Request $request);
	public function update(Model $model, Request $request);
	public function destroy(Model $model);
}
