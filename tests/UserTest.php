<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

	public function testGenerateUserAndVerifyAuthItsWorking()
	{
		$user = factory(App\User::class)->create(['password' => app('hash')->make('123456')]);

    	$this->post('/auth/login', ['email' => $user->email, 'password' => '123456'])->seeJsonStructure(['access_token']);
	}

}
