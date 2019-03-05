<?php

namespace Linnworks;

use GuzzleHttp\Client;

/**
 * Client to make requests to the Linnworks API.
 */
class LinnworksClient
{
    protected $client;
    protected $token;
    protected $server;

    const PRE_AUTH_BASE_URL = "https://api.linnworks.net/api/";

    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    /**
     * Create a new instance of the API client.
     *
     * @param Client|null $client
     * @param string|null $base_uri
     */
    public function __construct(?Client $client = null, string $base_uri = null)
    {
        $this->client = $client;
    }

    /**
     * Get the token property if it has been set.
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token ?? null;
    }

    /**
     * Get the server property if it has been set.
     *
     * @return string|null
     */
    public function getServer(): ?string
    {
        return $this->server ?? null;
    }

    /**
     * Set the token proprerty.
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = trim($token);
    }

    /**
     * Set the server property.
     *
     * @param string $server
     * @return void
     */
    public function setServer(string $server): void
    {
        $this->server = trim($server);
    }

    /**
     * Check whether a token or server are missing,
     * which will mean the request is unauthenticated.
     *
     * @return boolean
     */
    public function isUnauthenticated(): bool
    {
        return $this->getToken() === null || $this->getServer() === null;
    }

    /**
     * Get the authenticated or unauthenticated base URL for the request.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        if ($this->isUnauthenticated()) {
            return self::PRE_AUTH_BASE_URL;
        }

        return sprintf('%s/api/', $this->getServer());
    }

    /**
     * Make a GET request to the Linnworks API.
     *
     * @param string $uri
     * @param array $params
     * @return void
     */
    public function get(string $uri, array $params = [])
    {
        return $this->call(self::GET, $uri, $params);
    }

    /**
     * Make a POST request to the Linnworks API.
     *
     * @param string $uri
     * @param array $params
     * @return void
     */
    public function post(string $uri, array $params = [])
    {
        return $this->call(self::POST, $uri, $params);
    }

    /**
     * Make a PUT request to the Linnworks API.
     *
     * @param string $uri
     * @param array $params
     * @return void
     */
    public function put(string $uri, array $params = [])
    {
        return $this->call(self::PUT, $uri, $params);
    }

    /**
     * Make a DELETE request to the Linnworks API.
     *
     * @param string $uri
     * @param array $params
     * @return void
     */
    public function delete(string $uri, array $params = [])
    {
        return $this->call(self::DELETE, $uri, $params);
    }

    /**
     * Get a new API client instance.
     *
     * @return Client
     */
    protected function getClient(): Client
    {
        return $this->client ?? new Client;
    }

    /**
     * Get required request headers.
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
            "User-Agent" => "Linnworks PHP API SDK ",
            "Referer" => "https://www.linnworks.net/",
            // "Content-Length" => "" . strlen($data),
            "Authorization" => $this->getToken()
        ];
    }

    /**
     * Build the request body using the contents.
     *
     * @param array $body
     * @return array
     */
    protected function buildBody(string $method, array $params = []): array
    {
        if ($method === self::GET) {
            return [
                'query' => [
                    '_query' => $params
                ]
            ];
        }

        return [
            'body' => $params
        ];
    }

    /**
     * Execute a request to the Linnworks API.
     *
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return void
     */
    public function call(string $method, string $uri, array $params = [])
    {
        $body = $this->buildBody($method, $params);

        $response = $this->getClient()->request($method, $uri, $params);

        $opts = [
            'http'=> [
                'method'=> "POST" ,
                'header'=> $this->getHeaders(),
                'content' => $data
            ]
        ];

        $context = stream_context_create($opts);

        $response = file_get_contents($url, false, $context);

        return $response;
    }
}