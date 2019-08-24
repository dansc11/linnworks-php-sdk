<?php


namespace Linnworks\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Linnworks\LinnworksClient;
use PHPUnit\Framework\TestCase;

class LinnworksClientTest extends TestCase
{
    /**
     * The LinnworksClient instance to use throughout the tests.
     *
     * @var LinnworksClient
     */
    private $client;

    /**
     * This method is called before each test.
     *
     * Creates an instance of the LinnworksClient.
     */
    protected function setUp(): void
    {
        $sample_response_body = [
            "order_id" => 123,
            "order_name" => "test_order"
        ];

        $mock_request_handler = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode($sample_response_body)),
        ]);

        $handler = HandlerStack::create($mock_request_handler);
        $mock_client = new Client(['handler' => $handler]);

        $this->client = new LinnworksClient(null, null, $mock_client);

        parent::setUp();
    }

    /**
     * Test that setToken sets a token and getToken returns the same token.
     */
    public function testCanSetAndGetToken()
    {
        $token = "mytoken";
        $this->client->setToken($token);

        $this->assertEquals($token, $this->client->getToken());
    }

    /**
     * Test that getToken returns null before a token has been set.
     */
    public function testReturnsNullTokenBeforeTokenIsSet()
    {
        $this->assertNull($this->client->getToken());
    }

    /**
     * Test that setServer sets a server and getServer returns the same server.
     */
    public function testCanSetAndGetServer()
    {
        $token = "uk-server";
        $this->client->setServer($token);

        $this->assertEquals($token, $this->client->getServer());
    }

    /**
     * Test that getServer returns null before a server has been set.
     */
    public function testReturnsNullServerBeforeServerIsSet()
    {
        $this->assertNull($this->client->getServer());
    }

    /**
     * Test that isUnauthenticated returns false when server and token are both set.
     */
    public function testIsNotUnauthenticatedIfTokenAndServerAreSet()
    {
        $this->client->setToken('mytoken');
        $this->client->setServer('uk-server');

        $this->assertFalse($this->client->isUnauthenticated());
    }

    /**
     * Test that isUnauthenticated returns false when server is not set.
     */
    public function testIsUnauthenticatedIfServerIsNotSet()
    {
        $this->client->setToken('mytoken');

        $this->assertTrue($this->client->isUnauthenticated());
    }

    /**
     * Test that isUnauthenticated returns false when token is not set.
     */
    public function testIsUnauthenticatedIfTokenIsNotSet()
    {
        $this->client->setServer('uk-server');

        $this->assertTrue($this->client->isUnauthenticated());
    }

    /**
     * Test that getBaseUrl returns the unauthenticated base URL for unauthenticated requests.
     */
    public function testReturnsDefaultBaseUrlWhenUnauthenticated()
    {
        $this->assertEquals(LinnworksClient::PRE_AUTH_BASE_URL, $this->client->getBaseUrl());
    }

    /**
     * Test that getBaseUrl returns the base URL for the correct server when for authenticated requests.
     */
    public function testReturnsServerUrlWhenAuthenticated()
    {
        $server = "uk-server.linnworks.com";
        $this->client->setServer($server);
        $this->client->setToken('mytoken');

        $expected_base_url = $server . "/api/";

        $this->assertEquals($expected_base_url, $this->client->getBaseUrl());
    }

    /**
     * Test a POST request to the Linnworks API.
     */
    public function testMakesSuccessfulPostRequest()
    {
        $this->client->setToken('mytoken');
        $this->client->setServer('uk-server');

        $uri = "get-order";
        $body_params = [
            "order_id" => 123
        ];

        $response = $this->client->post($uri, $body_params);

        $this->assertEquals(123, $response->order_id);
        $this->assertEquals("test_order", $response->order_name);
    }

    private function getHeaders(string $token): array
    {
        return [
            "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
            "User-Agent" => "Linnworks PHP API SDK ",
            "Referer" => "https://www.linnworks.net/",
            "Authorization" => $token
        ];
    }
}