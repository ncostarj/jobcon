@extends('common.auth')
@section('title','Login')

@section('style')

@endsection

@section('script')

@endsection

@section('content')

<form method="post" action="{{ route('auth.authenticate') }}">
	@csrf
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
				<input type="password" id="password" name="password"  class="form-control"/>
				<label for="password">Senha</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<button class="btn btn-success" type="submit">Entrar</button>
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<a href="{{ route('auth.register.index') }}">Registre-se</a>
			<!-- <a href="{{-- route('password.request') --}}">Esqueceu a senha?</a> -->
        </div>
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
