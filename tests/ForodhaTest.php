<?php

namespace Openpesa\SDK\Tests;

use GuzzleHttp\Exception\GuzzleException;
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
                'output_ResponseCode' => 'INS-0',
                'output_ResponseDesc' => 'Request processed successfully',
                'output_SessionID' => 1
            ])),
            new Response(200, ['X-Foo' => 'Bar'], json_encode([
                'output_ResponseCode' => 'INS-0',
                'output_ResponseDesc' => 'Request processed successfully',
                'output_TransactionID' => 2,
                'output_ConversationID' => 'f1ddae567e6c45e580504764571dbe2f',
                'output_ThirdPartyConversationID' => 'Narration',
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
        $this->assertInstanceOf(Forodha::class, new Forodha([
            'api_key' => Fixture::$apiKey,
            'public_key' => Fixture::$publicKey,
            'username' => Fixture::$username,
            'auth_url' => Fixture::$authUrl,
            'client_options' => [],
        ]));
    }

    /** @test */
    public function forodha_has_these_attributes()
    {
        $this->assertClassHasAttribute('options', get_class($this->forodha));
        $this->assertClassHasAttribute('client', get_class($this->forodha));
    }

    /** @test
     * @throws GuzzleException
     */
    public function forodha_get_session()
    {
        // Arrange - Done in the set up method
        // Act
        $response = $this->forodha->get_session();
        // Assert
        $this->assertArrayHasKey('output_SessionID', $response);
        $this->assertEquals(1, $response['output_SessionID']);
    }

    /** @test
     * @throws GuzzleException
     */
    public function forodha_transact_c2b()
    {
        // Arrange - Done in the set up method
        $session = $this->forodha->get_session()['output_SessionID'];
        $result = $this->forodha->transact('c2b', Fixture::$data, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }
}