<?php

namespace App\Libs;

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
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * Broadcast the given event.
     *
     * @param  array $channels
     * @param  string $event
     * @param  array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = array())
    {
        \Log::info('PushStreamBroadcaster::broadcast');
        $payload = [
            'event' => $event,
            'data' => $payload,
        ];
        foreach ($channels as $channel) {
            $request = $this->client->createRequest('POST', '/pub?id=' . $channel, ['json' => $payload]);
            $response = $this->client->send($request);
        }
    }

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function auth($request)
    {
        \Log::info('PushStreamBroadcaster::auth');
        if (Str::startsWith($request->channel_name, ['private-', 'presence-']) &&
            ! $request->user()) {
            throw new HttpException(403);
        }

        $channelName = Str::startsWith($request->channel_name, 'private-')
                            ? Str::replaceFirst('private-', '', $request->channel_name)
                            : Str::replaceFirst('presence-', '', $request->channel_name);

        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );
    }

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        \Log::info('PushStreamBroadcaster::validAuthenticationResponse');
        if (is_bool($result)) {
            return json_encode($result);
        }

        return json_encode(['channel_data' => [
            'user_id' => $request->user()->getAuthIdentifier(),
            'user_info' => $result,
        ]]);
    }
}
