<?php

namespace Linnworks\Resources;

use GuzzleHttp\Exception\GuzzleException;
use Linnworks\LinnworksClient;
use Psr\Http\Message\ResponseInterface;

class BaseResource
{
    /**
     * @var LinnworksClient
     */
    protected $client;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * BaseResource constructor.
     */
    public function __construct()
    {
        $this->client = new LinnworksClient;
    }

    /**
     * Call a method on the Linnworks Client if it doesn't exist in the resource class.
     *
     * @param string $name
     * @param array $arguments
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function __call(string $name, array $arguments): ResponseInterface
    {
        $resource = get_class($this);
        $action = ucfirst($name);

        $uri = implode('/', [$resource, $action]);

        return $this->client->post($uri, $this->data);
    }

    public function __set(string $name, $value): void
    {
        if (property_exists($this, $name)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Get the name of the method which called the function.
     *
     * @return string
     */
    protected function getCallingMethod(): string
    {
        $trace = debug_backtrace();
        $caller = $trace[1];

        return $caller['function'];
    }
}