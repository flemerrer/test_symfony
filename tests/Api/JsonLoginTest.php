<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class JsonLoginTest extends ApiTestCase
{
    protected function setUp(): void {
        static::$alwaysBootKernel = true;
    }

    public function testLoginIsSuccessfulWithCorrectCredentials(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'grosminet',
                'password' => 'africa',
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $arrrayResp = $response->toArray();
        $this->assertArrayHasKey('token', $arrrayResp);
//        $this->assertNotEmpty($arrrayResp['token']);
        $this->assertNotTrue(empty($arrrayResp['token']));
    }

    public function testLoginFailsWithBadCredentials(): void
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'grosminet',
                'password' => 'badPassword',
            ]
        ]);

        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
