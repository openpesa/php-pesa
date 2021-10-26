<?php

namespace Openpesa\SDK\Tests;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Openpesa\SDK\Pesa;
use PHPUnit\Framework\TestCase;

/**
 * @property Pesa $pesa
 */
class PesaTest extends TestCase
{
    public function setup(): void
    {
        // Create a mock and queue responses.
        $mock = new MockHandler([

            // Session responses
            new Response(200, [], json_encode([
                'output_ResponseCode' => 'INS-0',
                'output_ResponseDesc' => 'Request processed successfully',
                'output_SessionID' => 1
            ])),

            // Transactions responses
            new Response(200, [], json_encode([
                'output_ResponseCode' => 'INS-0',
                'output_ResponseDesc' => 'Request processed successfully',
                'output_TransactionID' => 2,
                'output_ConversationID' => 'f1ddae567e6c45e580504764571dbe2f',
                'output_ThirdPartyConversationID' => 'Narration',
            ])),

        ]);

        $handlerStack = \GuzzleHttp\HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client(['handler' => $handlerStack, '' => Fixture::$apiUrl]);

        $this->pesa = new Pesa([
            'api_key' => Fixture::$apiKey,
            'public_key' => Fixture::$publicKey,
        ], $client);
    }

    /** @test */
    public function can_pesa_with_few_options_instantiable()
    {
        $this->assertInstanceOf(Pesa::class, $this->pesa);
        $this->assertInstanceOf(Pesa::class, new Pesa([
            'api_key' => Fixture::$apiKey,
            'public_key' => Fixture::$publicKey,
            'client_options' => [],
            'env' => 'sandbox'
        ]));
    }

    /** @test */
    public function can_pesa_with_many_options_instantiable()
    {

        $this->assertInstanceOf(Pesa::class, new Pesa([
            'api_key' => Fixture::$apiKey,
            'public_key' => Fixture::$publicKey,

            'persistent_session' =>  true,

            'country' => Fixture::$country,
            'currency' => Fixture::$currency,
            'service_provider_code' => Fixture::$service_provider_code,

            'client_options' => [],
            'env' => 'sandbox'
        ]));
    }



    /** @test */
    public function pesa_has_these_attributes()
    {
        $this->assertClassHasAttribute('options', get_class($this->pesa));
        $this->assertClassHasAttribute('client', get_class($this->pesa));
        $this->assertClassHasAttribute('rsa', get_class($this->pesa));
    }



    /**
     * @test
     * @throws GuzzleException
     */
    public function pesa_get_session()
    {
        // Arrange - Done in the set up method
        // Act
        $response = $this->pesa->getSession();
        // Assert
        $this->assertArrayHasKey('output_SessionID', $response);
        $this->assertEquals(1, $response['output_SessionID']);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function pesa_get_session_token()
    {
        // Arrange - Done in the set up method
        // Act
        $token = $this->pesa->getSessionToken();
        // Assert
        $this->assertEquals(1, $token);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function pesa_transact_c2b()
    {
        // Arrange - Done in the set up method
        $token = $this->pesa->getSessionToken();
        $result = $this->pesa->c2b(Fixture::$data_c2b, $token);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }


    /** @test
     * @throws GuzzleException
     */
    public function pesa_transact_b2b()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->b2b(Fixture::$data_b2b, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }

    /** @test
     * @throws GuzzleException
     */
    public function pesa_transact_b2c()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->b2c(Fixture::$data_b2c, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }

    /** @test
     * @throws GuzzleException
     */
    public function pesa_transact_reversal()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->reverse(Fixture::$data_reversal, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }

    /** @test
     * @throws GuzzleException
     */
    public function pesa_query_status()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->query(Fixture::$data_query, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }

    /** @test
     * @throws GuzzleException
     */
    public function pesa_transact_ddc()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->debit_create(Fixture::$data_ddc, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }

    /** @test
     * @throws GuzzleException
     */
    public function pesa_transact_ddp()
    {
        // Arrange - Done in the set up method
        $session = $this->pesa->getSessionToken();
        $result = $this->pesa->debit_payment(Fixture::$data_ddp, $session);
        // Act
        // Assert
        $this->assertArrayHasKey('output_ResponseCode', $result);
        $this->assertArrayHasKey('output_ResponseDesc', $result);
        $this->assertArrayHasKey('output_ConversationID', $result);
        $this->assertArrayHasKey('output_ThirdPartyConversationID', $result);
    }
}
