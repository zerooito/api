<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;
use App\Products;

class OrdersTest extends TestCase
{
	protected $User;

	public function testGetEmptyOrders()
	{
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];
		
		$url = '/v1/orders';

		$json = $this->get($url, $headers);

		$json->assertResponseStatus(200);
		
		$json->seeJsonContains([
			'per_page' => 15,
			'current_page' => 1,
			'prev_page_url' => env('APP_URL') . 'v1/orders?page=1',
			'next_page_url' => env('APP_URL') . 'v1/orders?page=2',
		]);

		$response = json_decode($json->response->getContent())->data;

		$this->assertEmpty($response);
	}

	public function testGetErrorOrdersLimitMajor100()
	{
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];
		
		$url = '/v1/orders?per_page=101';

		$json = $this->get($url, $headers);

		$json->assertResponseStatus(500);
	}

	public function testPostCreateOrder()
	{
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$url = '/v1/products';
		$data = [
			'sku' => 'TESTE1',
			'stock' => 10,
			'price' => 2.50,
			'name' => 'Produto de teste'
		];
		
		$json = $this->post($url, $data, $headers);

		$json->assertResponseStatus(201);

		$url = '/v1/orders';
		$data = [
			'value' => 7.45,
			'cust' => 3.00,
			'shipping' => 2.45,
			'sub_value' => 5.00,
			'status' => 'approved',
			'products' => [
				[
					'sku' => 'TESTE1',
					'quantity' => 2,
					'unit_value' => 2.50,
					'total' => 5.00
				]
			],
			'client' => [
				'firstname' => 'Reginaldo',
				'lastname' => 'Junior',
				'document' => '123.444.342-24',
				'email' => 'reginaldo@junior.com',
				'payer_info' => [
					'name' => 'Reginaldo Junior',
					'street' => 'Avenida do Contorno',
					'zipcode' => '07252015',
					'number' => '19',
					'neighborhood' => 'Nova Cidade',
					'city' => 'Guarulhos',
					'state' => 'SP',
					'complement' => 'Viela',
					'reference' => '',
					'country' => 'Brazil'
				],
				'receiver_info' => [
					'name' => 'Reginaldo Junior',
					'street' => 'Avenida Franscisco Matarrazo',
					'zipcode' => '05010000',
					'number' => '175',
					'neighborhood' => 'Perdizes',
					'city' => 'São Paulo',
					'state' => 'SP',
					'complement' => 'Viela',
					'reference' => '',
					'country' => 'Brazil'
				]
			]
		];

		$json = $this->post($url, $data, $headers);

		$json->assertResponseStatus(201);

        $product = Products::getItemBySKUAndUserId('TESTE1', $this->User->id);

		$this->assertEquals(8, $product['estoque']);
	}

	public function testAddInfoShippingInvoiceOrder()
	{
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$url = '/v1/products';
		$data = [
			'sku' => 'TESTE1',
			'stock' => 10,
			'price' => 2.50,
			'name' => 'Produto de teste'
		];
		
		$json = $this->post($url, $data, $headers);

		$url = '/v1/orders';
		$data = [
			'value' => 7.45,
			'cust' => 3.00,
			'shipping' => 2.45,
			'sub_value' => 5.00,
			'status' => 'approved',
			'products' => [
				[
					'sku' => 'TESTE1',
					'quantity' => 2,
					'unit_value' => 2.50,
					'total' => 5.00
				]
			],
			'client' => [
				'firstname' => 'Reginaldo',
				'lastname' => 'Junior',
				'document' => '123.444.342-24',
				'email' => 'reginaldo@junior.com',
				'payer_info' => [
					'name' => 'Reginaldo Junior',
					'street' => 'Avenida do Contorno',
					'zipcode' => '07252015',
					'number' => '19',
					'neighborhood' => 'Nova Cidade',
					'city' => 'Guarulhos',
					'state' => 'SP',
					'complement' => 'Viela',
					'reference' => '',
					'country' => 'Brazil'
				],
				'receiver_info' => [
					'name' => 'Reginaldo Junior',
					'street' => 'Avenida Franscisco Matarrazo',
					'zipcode' => '05010000',
					'number' => '175',
					'neighborhood' => 'Perdizes',
					'city' => 'São Paulo',
					'state' => 'SP',
					'complement' => 'Viela',
					'reference' => '',
					'country' => 'Brazil'
				]
			]
		];

		$json = $this->post($url, $data, $headers);

		$orderId = json_decode($json->response->getContent())->id;

		$url = '/v1/orders/' . $orderId;
		$data = [
			'shipments' => [
				'track_url' => 'http://correios.com/BR9341203948',
				'track_code' => 'BR9341203948',
				'nfe_key' => '23480293849',
				'nfe_serie' => '1',
				'nfe_number' => '239041892834',
				'company' => 'Correios',
				'service' => 'Sedex'
			]
		];

		$json = $this->patch($url, $data, $headers);

		$this->assertResponseStatus(200);
	}

}
