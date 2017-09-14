<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ClientTest extends TestCase
{

	public function testMethodNotFound()
	{
	    $url = '/v1/clients/lastss';

	    // Test unauthenticated access.
	    $this->get($url, $this->headers())
	         ->assertResponseStatus(404);
	}

	public function testMethodNotAuthorized()
	{
	    $url = '/v1/clients/last';

	    // Test unauthenticated access.
	    $this->get($url, $this->headers())
	         ->assertResponseStatus(401);
	}

	public function testReturnCountClients()
	{
	    $url = '/v1/clients/count';

	    $this->get(
	    	$url,
	    	$this->headers(\App\User::find(25))
	    )->assertResponseStatus(401);
	}

}
