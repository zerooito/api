<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

	protected $email;

	public function setUp()
	{
		parent::setUp();

		$this->email = uniqid() . '@teste.com';
	}

	public function testGenerateUserAndVerifyAuthItsWorking()
	{
		$user = factory(App\User::class)->create(['password' => app('hash')->make('123456')]);

    	$this->post(
    		'/auth/login',
    		[
    			'email' => $user->email,
    			'password' => '123456'
    		])->seeJsonStructure(['access_token']);
	}

	public function testCreateUser()
	{
		$data = [
			'name' => 'Reginaldo Junior',
			'email' => $this->email,
			'phone_area' => '11',
			'phone' => '208509184',
			'password' => '123456',
			'confirm_password' => '123456'
		];

		$url = '/v1/users';

		$json = $this->post($url, $data);

		$json->assertResponseStatus(201);
		$json->seeJsonStructure(['id', 'nome', 'email', 'telefone']);

		$data = [
			'email' => $this->email,
			'password' => '123456'
		];

    	$json = $this->post('/auth/login', $data);

    	$json->assertResponseStatus(200);
    	$json->seeJsonStructure(['access_token']);
	}

	public function testCaptureInfoUserByToken()
	{
		$user = $this->generateUserTest();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/users';
    	$json = $this->get($url, $this->headers);

    	$json->assertResponseStatus(200);
    	$json->seeJsonStructure(['access_token', 'name', 'email', 'phone']);
	}

}
