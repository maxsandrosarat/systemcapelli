<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//ADMIN
Route::group(['prefix' => 'admin'], function() {
    Route::get('/login', 'Auth\AdminLoginController@index')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    
    Route::get('/cadastros', 'AdminController@cadastros');

    Route::group(['prefix' => 'admin'], function() {
        Route::get('/', 'AdminController@indexAdmins');
        Route::post('/', 'AdminController@novoAdmin');
        Route::get('/filtro', 'AdminController@filtroAdmins');
        Route::post('/editar/{id}', 'AdminController@editarAdmin');
        Route::get('/ativar/{id}', 'AdminController@ativarAdmin');
    });

    Route::group(['prefix' => 'funcs'], function() {
        Route::get('/', 'AdminController@indexFuncionarios');
        Route::post('/', 'AdminController@cadastrarFuncionario');
        Route::post('/editar/{id}', 'AdminController@editarFuncionario');
        Route::get('/filtro', 'AdminController@filtroFuncionario');
        Route::get('/desvincularFuncFuncao/{p}/{d}', 'AdminController@desvincularFuncFuncao');
        Route::get('/ativar/{id}', 'AdminController@ativarFuncionario');
    });

    Route::group(['prefix' => 'servicos'], function() {
        Route::get('/', 'AdminController@indexServicos');
        Route::post('/', 'AdminController@cadastrarServico');
        Route::post('/editar/{id}', 'AdminController@editarServico');
        Route::get('/ativar/{id}', 'AdminController@ativarServico');
        Route::post('/promocao', 'AdminController@promocaoServico');
        Route::get('/promocao/finalizar/{id}', 'AdminController@promocaoServicoFinalizar');
    });

    Route::group(['prefix' => 'funcoes'], function() {
        Route::get('/', 'AdminController@indexFuncoes');
        Route::post('/', 'AdminController@cadastrarFuncao');
        Route::post('/editar/{id}', 'AdminController@editarFuncao');
    });

    Route::group(['prefix' => 'agendamentos'], function() {
        Route::get('/home', 'AdminController@agendamentos');
        Route::get('/', 'AdminController@indexAgendamentos');
        Route::get('/novo/{d}/{ho}', 'AdminController@novoAgendamento');
        Route::post('/', 'AdminController@cadastrarAgendamento');
        Route::get('/atendido/{id}', 'AdminController@atendidoAgendamento');
        Route::get('/cancelar/{id}', 'AdminController@cancelarAgendamento');
        Route::get('/editar/{id}', 'AdminController@editarAgendamento');
        Route::get('/filtro', 'AdminController@filtroAgendamento');
        Route::post('/config', 'AdminController@config');
        Route::get('/todos', 'AdminController@todosAgendamentos');
        Route::get('/todos/filtro', 'AdminController@filtroTodosAgendamento');
    });

    Route::group(['prefix' => 'usuarios'], function() {
        Route::get('/', 'AdminController@indexUsuarios');
        Route::post('/', 'AdminController@cadastrarUsuario');
        Route::get('/filtro', 'AdminController@filtroUsuario');
        Route::post('/editar/{id}', 'AdminController@editarUsuario');
        Route::get('/ativar/{id}', 'AdminController@ativarUsuario');
    });

    Route::group(['prefix' => 'banners'], function() {
        Route::get('/', 'AdminController@indexBanners');
        Route::post('/', 'AdminController@novoBanner');
        Route::post('/editar/{id}', 'AdminController@editarBanner');
        Route::get('/ativar/{id}', 'AdminController@ativarBanner');
        Route::get('/apagar/{id}', 'AdminController@apagarBanner');
    });
});

//JS
Route::post('/funcs', 'JSController@funcs');
Route::post('/agends/func', 'JSController@funcAgends');
Route::post('/agends/cliente', 'JSController@clienteAgends');

//CLIENTE
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dados', 'UserController@dados');
Route::post('/dados', 'UserController@editarDados');
Route::group(['prefix' => 'agendamentos'], function() {
    Route::get('/', 'UserController@indexAgendamento');
    Route::get('/painel', 'UserController@painelAgendamentos');
    Route::get('/novo/{d}/{h}/{s}/{f?}', 'UserController@novoAgendamento');
    Route::post('/', 'UserController@cadastrarAgendamento');
    Route::get('/cancelar/{id}', 'UserController@cancelarAgendamento');
    Route::get('/filtro', 'UserController@filtroAgendamento');
});

//FUNCIONARIO
Route::group(['prefix' => 'func'], function() {
    Route::get('/login', 'Auth\FuncLoginController@index')->name('func.login');
    Route::post('/login', 'Auth\FuncLoginController@login')->name('func.login.submit');
    Route::get('/', 'FuncController@index')->name('func.dashboard');
    Route::get('/dados', 'FuncController@dados');
    Route::post('/dados', 'FuncController@editarDados');
    Route::group(['prefix' => 'agendamentos'], function() {
        Route::get('/', 'FuncController@painelAgendamentos');
        Route::get('/atendido/{id}', 'FuncController@atendidoAgendamento');
        Route::get('/cancelar/{id}', 'FuncController@cancelarAgendamento');
        Route::get('/filtro', 'FuncController@filtroAgendamento');
    });
});


