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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/v1/orders/total', [
	'middleware' => 'jwt-auth',
	'uses' => 'OrdersController@getTotalOrders'
]);

$app->get('/v1/clients/last', [
	'middleware' => 'jwt-auth',
	'uses' => 'ClientsController@getLastClientsRegisters'
]);

$app->get('/v1/clients/count', [
	'middleware' => 'jwt-auth',
	'uses' => 'ClientsController@getTotalCountClients'
]);

$app->get('/v1/products/count', [
	'middleware' => 'jwt-auth',
	'uses' => 'ProductsController@getCountRegistersProducts'
]);

$app->get('/v1/products/cust', [
	'middleware' => 'jwt-auth',
	'uses' => 'ProductsController@getCustTotalProducts'
]);