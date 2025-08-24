@extends('common.auth')
@section('title','Login')

@section('style')

@endsection

@section('script')

@endsection

@section('content')

<form method="post" action="{{ route('auth.register.store') }}">
	@csrf
	<div class="row">
		<div class="col-4">
			<div class="form-floating mb-3">
				<input type="text" id="name" name="name"  class="form-control"/>
				<label for="name">Nome</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="form-floating mb-3">
				<input type="text" id="email" name="email"  class="form-control"/>
				<label for="email">E-mail</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="form-floating mb-3">
				<input type="text" id="email_comercial" name="email_comercial"  class="form-control"/>
				<label for="email">E-mail Comercial</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="form-floating mb-3">
				<input type="password" id="password" name="password"  class="form-control"/>
				<label for="password">Senha</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="form-floating mb-3">
				<input type="password" id="password_confirmation" name="password_confirmation"  class="form-control"/>
				<label for="password_confirmation">Confirmação de senha</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<button class="btn btn-success" type="submit">Salvar</button>
		</div>
	</div>

	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
</form>

@endsection
