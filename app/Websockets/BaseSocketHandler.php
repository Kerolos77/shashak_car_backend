<?php

namespace App\Websockets;

use Ratchet\ConnectionInterface;
use BeyondCode\LaravelWebSockets\Apps\App;
use Ratchet\WebSocket\MessageComponentInterface;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\ChannelManager;
abstract class BaseSocketHandler implements MessageComponentInterface
{
    protected $clients;
    protected $channelManager;
    public function __construct(ChannelManager $channelManager)
    {
        $this->channelManager = $channelManager;
    }
    protected function verifyAppKey(ConnectionInterface $connection)
    {
        $appKey = QueryParameters::create($connection->httpRequest)->get('appKey');

        if (!$app = App::findByKey($appKey)) {
            throw new UnknownAppKey($appKey);
        }

        $connection->app = $app;

        return $this;
    }
    protected function establishConnection(ConnectionInterface $connection)
    {
        $connection->send(json_encode([
            'event' => 'connection_established',
            'data' => json_encode([
                'socket_id' => $connection->socketId,
                'activity_timeout' => 30,
            ]),
        ]));
 

        StatisticsLogger::connection($connection);

        return $this;
    }
    protected function generateSocketId(ConnectionInterface $connection)
    {
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));

        $connection->socketId = $socketId;

        return $this;
    }
    public function onOpen(ConnectionInterface $connection)
    {
        $this->verifyAppKey($connection)->generateSocketId($connection)->establishConnection($connection);

        DashboardLogger::connection($connection);
    }
    public function onClose(ConnectionInterface $conn)
    {

        $this->channelManager->removeFromAllChannels($conn);
        DashboardLogger::disconnection($conn);
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }


}
