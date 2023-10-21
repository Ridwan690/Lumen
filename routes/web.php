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
$router->get('/posts', 'PostsController@index');

// Users
$router->get('/users', 'UserController@index');
$router->get('/users/{Id}', 'UserController@show');

// Book
$router->get('/books', 'BookController@index');

// Product
// $router->get('/product', 'ProductController@index');

$router->get('/barang', 'BarangsController@index');

// Address
$router->get('/address', 'AddressesController@index');

