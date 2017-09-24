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

}
