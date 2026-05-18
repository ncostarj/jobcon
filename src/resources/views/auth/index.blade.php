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
		<div class="col">
			<h1 class="text-center">JobCon</h1>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col">
			<div class="form-floating mb-3">
				<input type="text" id="email" name="email" placeholder="Email"  class="form-control"/>
				<label for="email">E-mail</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-floating mb-3">
				<input type="password" id="password" name="password" placeholder="Senha"  class="form-control"/>
				<label for="password">Senha</label>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<a href="{{ route('auth.register.index') }}">Cadastre-se</a><br/>
			<!--<a href="{{-- route('password.request') --}}">Esqueci a senha</a>-->
		</div>
		<div class="col text-end">
			<button class="btn btn-success" type="submit">Entrar</button>&nbsp;&nbsp;
		</div>
	</div>
	<!-- <div class="row">
		<div class="col-4">
			
        </div>
		</div>
	</div> -->

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
