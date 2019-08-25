<?php

namespace Linnworks\Tests\TestTraits;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

trait MocksGuzzleRequests
{
    protected function getMockGuzzleClient(): Client
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

        return $mock_client;
    }
}