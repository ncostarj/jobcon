@extends('common.layout')
@section('title', '- Ponto - Incluir')
@section('script')
<script type="text/javascript">
	// var app = new Vue({
	// 	el: '#horarioApp',
	// 	data: {
	// 		horarioForm: {
	// 			dia: '',
	// 			hora: ''
	// 		}
	// 	},
	// 	methods: {
	// 		setDate: function(date) {
	// 			let data = new Date();
	// 			this.horarioForm.dia = data.toISOString().replace(/(T[0-9]+\:[0-9]+\:[0-9]+\.[0-9]+Z)/,'');
	// 			this.horarioForm.hora = data.toLocaleTimeString();
	// 		}
	// 	},
	// 	computed: {},
	// 	created: function() {
	// 		this.setDate(new Date());

	// 	},
	// })
</script>
@endsection
@section('content')

<div class="row">
	<div class="col">
		<h1 class="text-center">Contracheque</h1>
	</div>
</div>
<div class="row" id="horarioApp">
	<div class="col-12">
		<form action="{{ $action }}" method="post">
			@if(isset($contracheque))
			@method('PUT')
			@endif
			@csrf
			<input type="hidden" name="usuario_id" value="{{ $usuario_id }}" />
			<div class="row">
				<div class="col">
					<div class="form-floating mb-3">
						<select id="empresa_id" class="form-select" name="empresa_id">
							<option value="">Selecione</option>
							@foreach($empresas as $empresa)
							<option @if(isset($contracheque) && $contracheque->empresa_id == $empresa->id) selected="selected" @endif value="{{ $empresa->id }}">{{ $empresa->razao_social }}</option>
							@endforeach
						</select>
						<label>Empresa: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="competencia" name="competencia" class="form-control" type="date" value="{{ isset($contracheque) ? $contracheque->competencia->format('Y-m-d') : '' }}">
						<label>Competencia: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<select id="tipo" class="form-select" name="tipo">
							<option value="">Selecione</option>
							<option @if(isset($contracheque) && $contracheque->tipo == 'regular') selected="selected" @endif value="regular">Regular</option>
							<option @if(isset($contracheque) && $contracheque->tipo == 'complementar') selected="selected" @endif value="complementar">Complementar</option>
							<option @if(isset($contracheque) && $contracheque->tipo == 'suplementar') selected="selected" @endif value="suplementar">Suplementar</option>
						</select>
						<label>Tipo: </label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-floating mb-3">
						<input id="salario_base" name="salario_base" class="form-control" type="text" value="{{ isset($contracheque) ? $contracheque->salario_base : '' }}">
						<label>Salário Base: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="salario_liquido" name="salario_liquido" class="form-control" type="text" value="{{ isset($contracheque) ? $contracheque->salario_liquido : '' }}">
						<label>Salário Líquido: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="total_vencimentos" name="total_vencimentos" class="form-control" type="text" value="{{ isset($contracheque) ? $contracheque->total_vencimentos : '' }}">
						<label>Total Vencimentos: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="total_descontos" name="total_descontos" class="form-control" type="text" value="{{ isset($contracheque) ? $contracheque->total_descontos : '' }}">
						<label>Total Descontos: </label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-success mb-3">Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
