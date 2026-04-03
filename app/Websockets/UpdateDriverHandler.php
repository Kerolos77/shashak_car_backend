<?php

namespace App\Websockets;

use stdClass;
use Exception;
use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Broadcast;
use Ratchet\RFC6455\Messaging\MessageInterface;

use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;

class UpdateDriverHandler extends BaseSocketHandler  implements MessageComponentInterface
// class UpdateDriverHandler implements MessageComponentInterface
{

    protected $subscribedConnections = [];
    protected $channelName;
    protected $channels = []; // Stores connections by channels

    protected stdClass $payload;



    function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $body = collect(json_decode($msg->getPayload(), true));
        $this->subscribedConnections[$connection->resourceId] = $connection;
        if ($body->get('action') === 'subscribe') {
            $channelName = $body->get('channel');
            $this->channelName = $channelName;
            $channel = $this->channelManager->findOrCreate($connection->app->id, $this->channelName);

            $channel->subscribe($connection, (object) ['channel' => $this->channelName]);
            if (!isset($this->channels[$channelName])) {
                $this->channels[$channelName] = [];
            }
            $this->channels[$channelName][$connection->resourceId] = $connection;


        }

        if ($body->get('action') === 'sendmessage') {
            $user = User::findToken($connection->httpRequest->getHeader('Authorization')[0]);
            // $channel = $body->get('channel');
            $channelName = $body->get('channel');
            $event = $body->get('event');
            $message = $body->get('message');
            $conversion = [
                'sender_id' => $user->id,
                'message' => $message,
            ];

            if (isset($this->channels[$channelName])) {
                foreach ($this->channels[$channelName] as $subscribedConnection) {
                    $subscribedConnection->send(json_encode([
                        'status' => 'message',
                        'data' => count($this->channels[$channelName]),
                    ]));
                }
            }

            $connection->send(json_encode([
                'status' => 'success',
                'message' => 'Message sent to channel: ' . $channelName
            ]));
            $connection->send(json_encode(['status' => 'success', 'event' => $event, 'data' => $conversion]));

        }

        // Example: Update user's location
        if ($body->get('action') === 'updateLocation') {
            $payload = $body->get('payload');
            $channelName = $body->get('channel');
            $user = User::findToken($connection->httpRequest->getHeader('Authorization')[0]);
            if ($user != null) {
                $user->update(['latitude' => $payload['lat'], 'longitude' => $payload['long']]);
                if (isset($this->channels[$channelName])) {
                    foreach ($this->channels[$channelName] as $subscribedConnection) {
                        $subscribedConnection->send(json_encode([
                            'status' => 'success',
                            'event' => 'location',
                            'user_id' => $user->id,
                            'data' => ['lat' => $payload['lat'], 'long' => $payload['long']],

                        ]));
                    }
                }


            } else {
                $connection->send(json_encode(['status' => 'error']));
            }
        }
    }
    public function subscribe(ConnectionInterface $connection)
    {
        $this->saveConnection($connection);

        $connection->send(json_encode([
            'event' => 'subscription_succeeded',
            'channel' => $this->channelName,
        ]));
    }

    public function hasConnections(): bool
    {
        return count($this->subscribedConnections) > 0;
    }

    protected function saveConnection(ConnectionInterface $connection)
    {
        $hadConnectionsPreviously = $this->hasConnections();
        $this->subscribedConnections[$connection->socketId] = $connection;
        if (!$hadConnectionsPreviously) {
            DashboardLogger::occupied($connection, $this->channelName);
        }
        DashboardLogger::subscribed($connection, $this->channelName);
    }

    public function broadcast($payload)
    {
        foreach ($this->subscribedConnections as $connection) {
            $connection->send(json_encode($payload));
        }
    }

}
