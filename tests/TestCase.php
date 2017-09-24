<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];
        
        if (!is_null($user)) {
            $headers['Authorization'] = 'Bearer ' . $user->access_token;
        }

        return $headers;
    }

    protected function generateUserTest()
    {
        $user = factory(App\User::class)->create(['password' => app('hash')->make('123456')]);

        $response = $this->post('/auth/login', ['email' => $user->email, 'password' => '123456']);

        return $response;
    }
    
}
