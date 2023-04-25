<?php
namespace Centras\Layers\Infrastructure\MessageBroker\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection
{
    /**
     * @return AMQPStreamConnection
     * @throws \Exception
     */
    public function connect(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            'rabbitmq',
            5672,
            'guest',
            'guest'
        );
    }
}
