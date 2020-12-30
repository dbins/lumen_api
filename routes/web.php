<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

$router->group([
    'prefix' => 'api/v1',
], function () use ($router){
    $router->get('/livros', 'LivrosController@index');
    $router->get('/livros/{id}', 'LivrosController@show');
    $router->post('/livros', 'LivrosController@store');
    $router->put('/livros/{id}', 'LivrosController@update');
    $router->delete('/livros/{id}', 'LivrosController@destroy');
	$router->post('/livros/contato', 'LivrosController@contact');
	$router->post('/livros/upload/{id}', 'LivrosController@upload');
	$router->get('/livros/upload/{id}', 'LivrosController@image');
	$router->post('auth/login', 'AuthController@authenticate');
	$router->get('/livros/exportar/{formato}', 'LivrosController@export');
});

$router->group(['middleware' => 'jwt.auth'], function() use ($router) {
    $router->get('/users', function() {
        $users = \App\Models\User::all();
        return response()->json($users);
    });
});

$router->get('/api/doc', 'LivrosController@docs');

;