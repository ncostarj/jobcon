<?php

use App\Http\Controllers\Api\CalendarioController;
use App\Http\Controllers\Api\ContrachequeController;
use App\Http\Controllers\Api\PontoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::prefix('v1')->name('api.v1.')->group(function () {

	Route::prefix('jobs')->name('jobs.')->group(function () {

		Route::prefix('calendario')->name('calendario.')->group(function(){

			Route::name('index')->get('/', [ CalendarioController::class, 'index' ]);

			// Route::name('index')->get('/', function (Request $request, CalendarioService $calendarioService) {
			// 	return $calendarioService->showCalendario();
			// });

			Route::name('listar_semana_atual')->get('/listar_semana_atual', function(Request $request, CalendarioService $calendarioService) {
				return $calendarioService->getCurrentWeek($request->all());
			});

			Route::name('listar_feriados')->get('/listar_feriados', function(Request $request, CalendarioService $calendarioService) {
				return $calendarioService->getFeriados();
			});
		});

		Route::prefix('pontos')->name('pontos.')->group(function () {

			Route::name('listar')->get('listar', [ PontoController::class, 'index' ]);
			Route::name('resumo')->get('resumo', [ PontoController::class, 'summarize' ]);
			Route::name('marcar')->post('marcar', [ PontoController::class, 'assign' ]);
			Route::name('listar_meses')->get('listar_meses', [ PontoController::class, 'indexMeses' ]);
			Route::name('calcular_subtotal')->get('calcular/subtotal', function (Request $request, PontoService $pontoService) {
			// 	return $pontoService->sumHours($request->all());
			});

			// Route::name('resumo')->get('resumo', function(Request $request, PontoService $pontoService) {
			// 	return $pontoService->summarize($request->all());
			// });
		});

		Route::prefix('frequencias')->name('frequencias.')->group(function(){
			Route::name('listar')->get('listar', function (Request $request, FrequenciaService $frequenciaService) {
				return $frequenciaService->get($request->all());
			});

			Route::name('listarUltimoSaldo')->get('listarUltimoSaldo', function (Request $request, FrequenciaService $frequenciaService) {
				return $frequenciaService->getLastSaldo($request->all());
			});
		});

		Route::prefix('ferias')->name('ferias.')->group(function () {

			Route::name('listar')->get('listar', function (Request $request, FeriasService $feriasService) {
				return $feriasService->get($request->all());
			});

			Route::name('verificar')->get('verificar', function (Request $request, FeriasService $feriasService) {
				return $feriasService->verifyDiasAteFerias($request->all());
			});
		});

		Route::prefix('escalas')->name('escalas.')->group(function () {
			Route::get('/listar', function(Request $request, EscalaService $escalaService) {
                return $escalaService->search($request->all());
            })->name('listar');
		});

		Route::prefix('tarefas')->name('tarefas.')->group(function () {
			Route::get('/sprints', function(Request $request, JiraService $jiraService) {
				return $jiraService->getSprints();
			})->name('sprints');

			Route::get('/projetos', function(Request $request, JiraService $jiraService) {
				return $jiraService->getProjetos();
			})->name('projetos');

			Route::get('/usuarios', function(Request $request, JiraService $jiraService) {
				return $jiraService->getAllUsuarios();
			})->name('usuarios');

			Route::get('/buscar', function(Request $request, JiraService $jiraService) {
				return $jiraService->searchTarefas($request->all());
			})->name('buscar');

			Route::post('salvar', function(Request $request, JiraService $jiraService) {
				return $jiraService->storeTarefa($request->all());
			})->name('salvar');

			Route::post('salvar_registro_trabalho', function($issueKeyOrId, Request $request, JiraService $jiraService) {
				return $jiraService->storeWorklog($issueKeyOrId, $request->all());
			})->name('salvar_registro_trabalho');

			Route::get('/statuses/buscar', function(Request $request, JiraService $jiraService) {
				return $jiraService->searchStatuses($request->all());
			})->name('buscar_statuses');
		// 	Route::get('/listar', 'TarefasService@listar')->name('listar');
		// 	// Route::get('/kanban', [JiraController::class, 'kanban'])->name('kanban');
		// 	// Route::get('/tickets', [JiraController::class, 'tarefas'])->name('tickets');
		// 	// Route::get('/usuarios', [JiraController::class, 'getUsuarios'])->name('users');
		// 	// Route::get('/paineis', [JiraController::class, 'getPaineis'])->name('boards');
		// 	// Route::get('/sprints', [JiraController::class, 'getSprints'])->name('sprints');
		});

		Route::prefix('contracheques')->name('contracheques.')->group(function(){
			Route::name('listar')->get('listar', [ ContrachequeController::class, 'index' ]);
			Route::name('listar_anos')->get('listar_anos', [ ContrachequeController::class, 'indexAnos' ]);
		});
	});

	Route::prefix('spotify')->name('spotify.')->group(function () {
		// Route::get('/', [SpotifyController::class, 'index'])->name('index');
		// Route::get('/allow', [SpotifyController::class, 'allow'])->name('allow');
		// Route::get('/profile', [SpotifyController::class, 'getProfile'])->name('profile');
	});
});
