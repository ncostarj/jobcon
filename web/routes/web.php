<?php

use App\Http\Controllers\ContrachequeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeriasController;
use App\Http\Controllers\PontoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\TarefaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ LoginController::class, 'index' ])->name('login');

Route::prefix('auth')->name('auth.')->group(function() {
	Route::post('/', [ LoginController::class, 'authenticate' ])->name('authenticate');
	Route::get('/cadastro', [ LoginController::class, 'registerIndex' ])->name('register.index');
	Route::post('/cadastro', [ LoginController::class, 'registerStore' ])->name('register.store');
	Route::get('/logout', [ LoginController::class, 'logout' ])->name('logout');
});

//
Route::group(['middleware' => 'auth'], function(){

		Route::get('/home', function(){
			return view('index');
		})->name('home');

		Route::prefix('jobs')->name('jobs.')->group(function () {

			Route::prefix('dashboard')->name('dashboard.')->group(function () {
				Route::get('/', [DashboardController::class, 'index'])->name('index');
				Route::get('/agenda', [DashboardController::class, 'agendaIndex'])->name('agenda_index');
			});

			Route::prefix('pontos')->name('pontos.')->group(function () {
				Route::get('/', [ PontoController::class, 'index' ])->name('index');
				Route::get('/criar', [ PontoController::class, 'create' ])->name('create');
				Route::get('/editar/{id}', [ PontoController::class, 'edit' ])->name('edit');
				Route::put('/{id}/atualizar', [ PontoController::class, 'update' ])->name('update');
				Route::put('/{id}/apagar', [ PontoController::class, 'destroy' ])->name('destroy');
			});

			Route::prefix('tarefas')->name('tarefas.')->group(function(){
				Route::get('/criar', [ TarefaController::class, 'create' ])->name('create');
			});

			Route::prefix('contracheques')->name('contracheques.')->group(function () {
				Route::get('/', [ ContrachequeController::class, 'index' ])->name('index');
				Route::get('/criar', [ ContrachequeController::class, 'create' ])->name('create');
				Route::post('/salvar', [ ContrachequeController::class, 'store' ])->name('store');
				Route::get('/editar/{contracheque}', [ ContrachequeController::class, 'edit' ])->name('edit');
				Route::put('/{contracheque}/atualizar', [ ContrachequeController::class, 'update' ])->name('update');
				Route::delete('/{contracheque}/apagar', [ ContrachequeController::class, 'destroy' ])->name('destroy');
			});

			Route::prefix('ferias')->name('ferias.')->group(function () {
				Route::get('/', [ FeriasController::class, 'index' ])->name('index');
				Route::get('/criar', [ FeriasController::class, 'create' ])->name('create');
				Route::post('/salvar', [ FeriasController::class, 'store' ])->name('store');
				Route::get('/editar/{id}', [ FeriasController::class, 'edit' ])->name('edit');
				Route::put('/{id}/atualizar', [ FeriasController::class, 'update' ])->name('update');
				Route::delete('/{id}/apagar', [ FeriasController::class, 'destroy' ])->name('destroy');
			});
		});

		Route::prefix('spotify')->name('spotify.')->group(function () {
			Route::get('/', [SpotifyController::class, 'index'])->name('index');
			Route::get('/allow', [SpotifyController::class, 'allow'])->name('allow');
			Route::get('/profile', [SpotifyController::class, 'getProfile'])->name('profile');
		});

	});
