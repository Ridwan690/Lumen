<?php
use App\Http\Controllers\BarangController;

/** @var \Laravel\Lumen\Routing\Router $router */

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/', function () {
    return response()->json([
        'service_name' => 'PHP Service App',
        'status' => 'Running'
    ]);
});

$router->get('/hello-lumen/{name}', function ($name) {
    return "<h1>Lumen</h1><p>Hi <b>" . $name ."</b>,thank for using Lumen</p>"; 
});

$router->get('/scores', ['middleware' => 'login', function () {
    return '<h1>Selamat</h1> <p>Nilai anda 100</p>';
}]);

// $router->get('/users', 'UsersController@index');


// $router->get('/users', 'UserController@index');
// $router->get('/users/{userId}', 'UserController@getUserById');

// $router->get('/product', 'ProductController@index');
$router->get('/home', 'HomeController@index');
$router->get('/profil', 'ProfilController@index');
$router->get('/dashboard', 'DashboardController@index');


$router->get('/login', ['middleware' => 'login', function () {
    return response()->json(['message' => 'This is a secure route']);
}]);

$router->get('/admin', ['middleware' => 'admin', function () {
    return response()->json(['message' => 'Admin Dashboard']);
}]);

$router->get('/secure', ['middleware' => 'secure', function () {
    return response()->json(['message' => 'Secure']);
}]);

$router->get('/register', ['middleware' => 'regis', function () {
    return response()->json(['message' => 'Register']);
}]);

$router->get('/landingpage', ['middleware' => 'user', function () {
    return "<h1>Hallo, Selamat anda berhasil login sebagai user</h1>";
}]);


// Post
Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('/posts', 'PostsController@index');
    $router->post('/posts', 'PostsController@store');
    $router->get('/posts/{id}', 'PostsController@show');
    $router->put('/posts/{id}', 'PostsController@update');
    $router->delete('/posts/{id}', 'PostsController@destroy');
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

});


// Users
$router->get('/users', 'UserController@index');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->put('/users/{id}', 'UserController@update');
$router->delete('/users/{id}', 'UserController@destroy');

// Book
Route::group(['middleware' => ['auth']], function ($router) {
    $router->get('/books', 'BookController@index');
    $router->post('/books', 'BookController@store');
    $router->get('/books/{id}', 'BookController@show');
    $router->put('/books/{id}', 'BookController@update');
    $router->delete('/books/{id}', 'BookController@destroy');
});

// Product
// $router->get('/product', 'ProductController@index');

//Barang
$router->get('/barang', 'BarangsController@index');
$router->post('/barang', 'BarangsController@store');
$router->get('/barang/{id}', 'BarangsController@show');
$router->put('/barang/{id}', 'BarangsController@update');
$router->delete('/barang/{id}', 'BarangsController@destroy');

// Address
$router->get('/address', 'AddressesController@index');
$router->post('/address', 'AddressesController@store');
$router->get('/address/{id}', 'AddressesController@show');
$router->put('/address/{id}', 'AddressesController@update');
$router->delete('/address/{id}', 'AddressesController@destroy');

