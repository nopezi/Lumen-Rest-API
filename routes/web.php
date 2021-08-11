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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/tes', function() {
	return 'helo mencoba';
});

$router->get('/blog', 'BlogController@index');
$router->get('/blog/cari', 'BlogController@cari');
$router->post('/blog/tambah', 'BlogController@tambah');
$router->post('/blog/edit', 'BlogController@edit');
$router->delete('/blog/hapus', 'BlogController@hapus');

$router->get('/kategori', 'KategoriController@index');
$router->post('/kategori/tambah', 'KategoriController@tambah');
$router->put('/kategori/edit', 'KategoriController@edit');
$router->delete('/kategori/hapus', 'KategoriController@hapus');

# testing api
$router->get('/api/tes/pertama', 'TestController@palindrome');
$router->get('/api/tes/kedua', 'TestController@merge');

$router->group([
	'prefix' => 'auth'
], function() use ($router) {
	$router->post('/register','AuthController@register');
	$router->post('/login','AuthController@login');
});