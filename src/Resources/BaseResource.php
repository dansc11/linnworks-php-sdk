<?php

namespace Linnworks\Resources;

use GuzzleHttp\ClientInterface;
use Linnworks\LinnworksClient;
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
     * @param ClientInterface|null $client
     */
    public function __construct(?string $server = null, string $token = null, ?ClientInterface $client = null)
    {
        $this->client = $client ?? new LinnworksClient($server, $token);
    }

    /**
     * Call a method on the Linnworks Client if it doesn't exist in the resource class.
     *
     * @param string $name
     * @param array $arguments
     * @return object
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments): object
    {
        $reflect = new ReflectionClass($this);
        $resource = $reflect->getShortName();

        $action = ucfirst($name);

        $uri = implode('/', [$resource, $action]);

        return $this->client->post($uri, $this->data);
    }

    /**
     * Set valid properties in the $data array.
     *
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (property_exists($this, $name)) {
            $this->data[$name] = $value;
        }
    }
}