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

}
