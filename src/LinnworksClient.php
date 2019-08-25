<?php

namespace Linnworks;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Client to make requests to the Linnworks API.
 */
class LinnworksClient
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $server;

    const PRE_AUTH_BASE_URL = "https://api.linnworks.net/api/";

    /**
     * Create a new instance of the API client.
     *
     * @param string|null $server
     * @param string|null $token
     * @param ClientInterface|null $client
     */
    public function __construct(?string $server = null, ?string $token = null, ?ClientInterface $client = null)
    {
        if ($server !== null) {
            $this->setServer($server);
        }

        if ($token !== null) {
            $this->setToken($token);
        }

        if ($client !== null) {
            $this->client = $client;
        }
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
     * Set the token property.
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
     * Make a POST request to the Linnworks API.
     *
     * @param string $uri
     * @param array $params
     * @return object
     */
    public function post(string $uri, array $params = []): object
    {
        $url = $this->getBaseUrl() . $uri;
        $body = $this->buildPayload($params);

        $response = $this->getClient()->post($url, $body);
        $contents = $response->getBody()->getContents();

        return json_decode($contents);
    }

    /**
     * Get a new API client instance.
     *
     * @return ClientInterface
     */
    protected function getClient(): ClientInterface
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
            "Authorization" => $this->getToken()
        ];
    }

    /**
     * Build the request body using the contents.
     *
     * @param array $params
     * @return array
     */
    protected function buildPayload(array $params = []): array
    {
        return [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ];
    }
}