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

$app->post('/auth/login', [
	'uses' => 'AuthController@postLogin'
]);

$app->put('/auth/refresh', [
	'uses' => 'AuthController@refresh'
]);

$app->group(['prefix' => '/v1'], function() use ($app) {

	$app->group(['prefix' => '/orders'], function() use ($app) {

		$app->get('/load', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@loadOrdersByPeriod'
		]);

		$app->get('/total', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@getTotalOrders'
		]);

		$app->post('/', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@create'
		]);

		$app->get('/', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@get'
		]);

		$app->get('/{id}', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@getById'
		]);

		$app->patch('/{id}', [
			'middleware' => 'jwt-auth',
			'uses' => 'OrdersController@patch'
		]);
			
	});

	$app->group(['prefix' => '/clients'], function() use ($app) {			

		$app->post('/', [
			'middleware' => 'jwt-auth',
			'uses' => 'ClientsController@registerClient'
		]);

		$app->get('/last', [
			'middleware' => 'jwt-auth',
			'uses' => 'ClientsController@getLastClientsRegisters'
		]);

		$app->get('/count', [
			'middleware' => 'jwt-auth',
			'uses' => 'ClientsController@getTotalCountClients'
		]);

	});

	$app->group(['prefix' => '/products'], function() use ($app) {

		$app->get('/', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@get'
		]);

		$app->post('/', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@create'
		]);

		$app->patch('/{sku}', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@patchBySku'
		]);

		$app->get('/count', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@getCountRegistersProducts'
		]);

		$app->get('/cust', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@getCustTotalProducts'
		]);

		$app->get('/{sku}', [
			'middleware' => 'jwt-auth',
			'uses' => 'ProductsController@getProductBySku'
		]);

	});

	$app->group(['prefix' => '/variations'], function() use ($app) {

		$app->patch('/{sku}', [
			'middleware' => 'jwt-auth',
			'uses' => 'VariationsController@patchBySku'
		]);

	});

	$app->group(['prefix' => '/users'], function() use ($app) {
		
		$app->post('/', [
			'uses' => 'UsersController@create'
		]);

	});

});