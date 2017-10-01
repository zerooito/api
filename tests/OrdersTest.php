<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class OrdersTest extends TestCase
{

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

}
