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
		<h1 class="text-center">Férias</h1>
	</div>
</div>
<div class="row" id="horarioApp">
	<div class="col-12">
		<form action="{{ $action }}" method="post">
			@if(isset($ferias))
			@method('PUT')
			@endif
			@csrf
			<input type="hidden" name="usuario_id" value="{{ $usuario_id }}" />
			<div class="row">
				<div class="col">
					<div class="form-floating mb-3">
						<input id="inicio" name="inicio" class="form-control" type="date" value="{{ isset($ferias) ? $ferias->inicio->format('Y-m-d') : '' }}">
						<label>Início: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="fim" name="fim" class="form-control" type="date" value="{{ isset($ferias) ? $ferias->fim->format('Y-m-d') : '' }}">
						<label>Fim: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="qtd_dias" name="qtd_dias" class="form-control" type="number" value="{{ isset($ferias) ? $ferias->qtd_dias : '' }}">
						<label>Dias: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="observacao" name="observacao" class="form-control" type="text" value="{{ isset($ferias) ? $ferias->observacao : '' }}">
						<label>Observação: </label>
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
