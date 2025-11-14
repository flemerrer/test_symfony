<?php

    namespace App\Tests\Api;

    use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

    class ApiCategoriesTest extends ApiTestCase
    {

        protected function setUp(): void
        {
            static::$alwaysBootKernel = true;
        }

        public function testCategoriesRoutesAreUnauthorizedWithoutToken(): void
        {
            $response = static::createClient()->request('GET', '/apiTest/categories');
            $this->assertEquals(401, $response->getStatusCode());
        }

        public function testCategoriesRoutesAreAccessibleWithToken(): void
        {
            $client = static::createClient();
            $response = $client->request('GET', '/api/login_check', [
                'json' => [
                    'username' => 'titi',
                    'password' => 'africa'
                ]
            ]);
            $token = $response->toArray()['token'];
            $response = $client->request('GET', '/apiTest/categories', [
                'headers' => ["Authorization" => "Bearer $token"]
            ]);
            $this->assertEquals(200, $response->getStatusCode());
        }
    }
