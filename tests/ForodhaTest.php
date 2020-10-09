<?php

namespace Openpesa\SDK\Tests;

use Openpesa\SDK\Forodha;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

/**
 * @property Forodha $forodha
 */
class ForodhaTest extends TestCase
{
    public function setup()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode([
                'output_SessionID' => 1
            ])),
            new Response(200, ['X-Foo' => 'Bar'], json_encode([
                'status' => 1
            ])),
            new Response(202, ['Content-Length' => 0]),
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $this->forodha = new Forodha([
            'api_key' => Fixture::$apiKey,
            'public_key' => Fixture::$publicKey,
            'username' => Fixture::$username,
            'auth_url' => Fixture::$authUrl,
        ], $client);

    }

    /** @test */
    public function forodha_instantiable()
    {
        $this->assertInstanceOf(Forodha::class, $this->forodha);
    }

    /** @test */
    public function forodha_has_these_attributes()
    {
        $this->assertClassHasAttribute('options', get_class($this->forodha));
        $this->assertClassHasAttribute('client', get_class($this->forodha));
    }

    /** @test
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function forodha_generate_session()
    {
        // Arrange - Done in the set up method

        // Act
        $response = $this->forodha->generate_session();
        // Assert
        $this->assertArrayHasKey('output_SessionID', $response);
        $this->assertEquals(1, $response['output_SessionID']);
    }

    /** @test
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function forodha_transact()
    {
        // Arrange - Done in the set up method
        $this->forodha->generate_session();
        $result = $this->forodha->transact(Fixture::$data);
        // Act
        // Assert
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(1, $result['status']);
    }
}
