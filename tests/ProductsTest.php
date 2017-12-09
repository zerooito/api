<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\User;
use App\Products;

class ProductsTest extends TestCase
{

	protected $User;
	protected $headers;

	public function testCreateProductBasicInfoByEndpoint()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);
	}

	public function testCreateProductWithVariations()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTEEDIT',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTEEDIT-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTEEDIT-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);
		$json->seeJsonContains(['variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTEEDIT-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTEEDIT-G-PRETO',
					'stock' => 8
				]
			]
		]);
	}

	public function testEditProductSimpleInfo()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);

		$url = '/v1/products/TESTE1';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste editado',
			'stock' => '10',
			'price' => 9.99,
		];

		$json = $this->patch($url, $data, $this->headers);

		$json->assertResponseStatus(200);
	}

	public function testEditProductWithVariationNotExist()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/variations/TESTE1-M-PRETO123';

		$data = [
			'name' => 'Produto Teste > M > Preto',
			'price' => 9.99,
			'sku' => 'TESTE1-M-PRETO123',
			'stock' => 8
		];

		$json = $this->patch($url, $data, $this->headers);

		$json->assertResponseStatus(400);
		$json->seeJsonContains(['error' => 'Variation not found']);
	}

	public function testEditProductWithVariationExistButSkuNot()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);

		$url = '/v1/variations/TESTE1-M-PRETO';

		$data = [
			'name' => 'Produto Teste > M > Preto',
			'price' => 9.99,
			'sku' => 'TESTE1-M-PRETO123',
			'stock' => 8
		];

		$json = $this->patch($url, $data, $this->headers);

		$json->assertResponseStatus(400);
		$json->seeJsonContains(['error' => 'SKU URI not match with data']);
	}

	public function testEditProductWithVariation()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);

		$url = '/v1/variations/TESTE1-M-PRETO';

		$data = [
			'name' => 'Produto Teste > M > Azul > 123',
			'price' => 9.99,
			'sku' => 'TESTE1-M-PRETO',
			'stock' => 8
		];

		$json = $this->patch($url, $data, $this->headers);

		$json->assertResponseStatus(200);
		$json->seeJsonContains([
			'name' => 'Produto Teste > M > Azul > 123',
			'price' => 9.99,
			'sku' => 'TESTE1-M-PRETO',
			'stock' => 8
		]);
	}

	public function testGetProductCompleteWithVariations()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);

		$url = '/v1/products/TESTE1';
		$json = $this->get($url, $this->headers);
		$json->assertResponseStatus(200);
		$json->seeJsonContains([
		   'sku' => 'TESTE1',
		   'name' => 'Produto teste',
		   'stock' => 10,
		   'price' => 9.99,
		   'variations' => [
			    [
			       'name' => 'Produto Teste > M > Preto',
			       'price' => 9.99,
			       'sku' => 'TESTE1-M-PRETO',
			       'stock' => 8,
			    ],
			    [
			       'name' => 'Produto Teste > G > Preto',
			       'price' => 9.99,
			       'sku' => 'TESTE1-G-PRETO',
			       'stock' => 8,
			    ],
			]
		]);
	}

	public function testTryDuplicateSKU()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
		];

		$jsonOne = $this->post($url, $data, $this->headers);
		$jsonOne->assertResponseStatus(201);

		$jsonTwo = $this->post($url, $data, $this->headers);
		$jsonTwo->assertResponseStatus(400);
		$jsonTwo->seeJsonContains(['error' => 'SKU is send already exist']);
	}

	public function testRemoveSku()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
		];

		$jsonOne = $this->post($url, $data, $this->headers);
		$jsonOne->assertResponseStatus(201);

		$url = '/v1/products/TESTE1';
		$jsonDelete = $this->delete($url, [], $this->headers);
		$jsonDelete->assertResponseStatus(200);
	}

	public function testEditProductSendAllBodyIncludeVariations()
	{
		$user = $this->generateUserTest();

		$this->User = User::where('token', $user->response->original['access_token'])->first();

		$this->headers['Authorization'] = 'Bearer ' . $user->response->original['access_token'];

		$url = '/v1/products';

		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->post($url, $data, $this->headers);

		$json->assertResponseStatus(201);

		$url = '/v1/products/TESTE1';
		$data = [
			'sku' => 'TESTE1',
			'name' => 'Produto teste',
			'stock' => '10',
			'price' => 9.99,
			'variations' => [
				[
					'name' => 'Produto Teste > M > Preto > Editado',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto > Editado',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		];

		$json = $this->patch($url, $data, $this->headers);
		$json->assertResponseStatus(200);
		$json->seeJsonContains(['variations' => [
				[
					'name' => 'Produto Teste > M > Preto > Editado',
					'price' => 9.99,
					'sku' => 'TESTE1-M-PRETO',
					'stock' => 8
				],
				[
					'name' => 'Produto Teste > G > Preto > Editado',
					'price' => 9.99,
					'sku' => 'TESTE1-G-PRETO',
					'stock' => 8
				]
			]
		]);
	}

}
