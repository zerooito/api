<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Andresses;

class AndressesModelTest extends TestCase
{

	protected $Andresses;

	public function testGetAndressPayerByClientAndUserIdEmpty()
	{
		$this->Andresses = new Andresses;

		$this->assertEmpty($this->Andresses->getAndressPayerByClientAndUserId(1, 1, 1));
	}

	public function testRegisterNewAndressClient()
	{
		$this->Andresses = new Andresses;

		$user = $this->generateUserTest();

		$data = [
			'zipcode' => '09181000',
			'street' => 'Avenida do Testes',
			'number' => '19',
			'neighborhood' => 'Jardim Carvalho',
			'city' => 'Santo Ándre',
			'state' => 'SP',
			'name' => 'Reginaldo Junior',
			'complement' => 'Viela',
			'reference' => 'Proximo a avenida dos padroes',
			'country' => 'Brasil'
		];

		$headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$userInfo = $this->get('/v1/users', $headers);
		$userInfo = json_decode($userInfo->response->getContent());

		$response = $this->Andresses->registerAddress('pagador', $userInfo->id, $data, 1);

		$this->assertEquals($data, $response);
	}

}