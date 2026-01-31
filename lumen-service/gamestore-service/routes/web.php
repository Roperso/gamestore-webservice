<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
$router->delete('/auth/logout', ['middleware' => 'jwt.auth', 'uses' => 'AuthController@logout']);

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/games', 'GameController@index');
    $router->get('/game/{id}', 'GameController@show');
    $router->post('/games', 'GameController@store');
    $router->put('/game/{id}', 'GameController@update');
    $router->delete('/game/{id}', 'GameController@destroy');
});

$router->get('/public/games', 'PublicGameController@index');
$router->get('/public/game/{id}', 'PublicGameController@show');

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/categories', 'CategoryController@index');
    $router->post('/categories', 'CategoryController@store');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/game/{gameId}/image', 'GameImageController@upload');
});

$router->get('/games/image/{filename}', 'GameImageController@show');

// PUBLIC
$router->get('/public/game/{gameId}/reviews', 'ReviewController@index');

// LOGIN
$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/game/{gameId}/reviews', 'ReviewController@store');
    $router->delete('/reviews/{id}', 'ReviewController@destroy');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/game/{gameId}/purchase', 'TransactionController@purchase');
    $router->get('/my-transactions', 'TransactionController@myTransactions');
    $router->get('/transactions', 'TransactionController@allTransactions');
});

$router->get('/public/games/xml', 'PublicGameController@xml');
