<?php

namespace Linnworks\Resources;

use Linnworks\LinnworksClient;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;
use ReflectionException;

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
     *
     * @param string|null $server
     * @param string|null $token
     */
    public function __construct(?string $server = null, string $token = null)
    {
        $this->client = new LinnworksClient($server, $token);
    }

    /**
     * Call a method on the Linnworks Client if it doesn't exist in the resource class.
     *
     * @param string $name
     * @param array $arguments
     * @return StreamInterface
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments): StreamInterface
    {
        $reflect = new ReflectionClass($this);
        $resource = $reflect->getShortName();

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