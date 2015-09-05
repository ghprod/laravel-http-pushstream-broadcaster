<?php

namespace Cmosguy\Broadcasting\Broadcasters;

use GuzzleHttp\Client;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class PushStreamBroadcaster implements Broadcaster
{
    /**
     * @var Client
     */
    private $client;

    /**
     * PushStreamBroadcaster constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Broadcast the given event.
     *
     * @param array  $channels
     * @param string $event
     * @param array  $payload
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach ($channels as $channel) {
            $payload = [
                'text' => $payload,
            ];

            // merge default query with channel id
            $query    = array_merge([
                'id' => $channel,
            ], $this->client->getDefaultOption('query'));

            $request  = $this->client->createRequest('POST', '/pub', [
                'query' => $query,
                'json'  => $payload,
            ]);

            $response = $this->client->send($request);
        }
    }
}
