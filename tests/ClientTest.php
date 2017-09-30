<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ClientTest extends TestCase
{

	public function testMethodNotFound()
	{
	    $url = '/v1/clients/lastss';

	    $this->get($url, $this->headers())
	         ->assertResponseStatus(404);
	}

	public function testClientLastNoContent()
	{
		$url = '/v1/clients/last';
		
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];
		
		$this->get($url, $headers)->assertResponseStatus(204);
	}

	public function testClientLastContentOK()
	{
		$user = $this->generateUserTest();

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];
		
		$data = [
			'firstname' => 'Reginaldo',
			'lastname' => 'Junior',
			'password' => '123456',
			'email' => uniqid() . '@teste.com'
		];

		$url = '/v1/clients';

		$json = $this->post($url, $data, $headers);

		$json->assertResponseStatus(201);

		$url = '/v1/clients/last';

		$getResponse = $this->get($url, $headers);

		$getResponse->assertResponseStatus(200);
		$getResponse->seeJsonContains(['nome' => 'Reginaldo Junior']);
	}

}
