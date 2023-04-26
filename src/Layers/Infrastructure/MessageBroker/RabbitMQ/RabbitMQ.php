<?php

namespace Centras\Layers\Infrastructure\MessageBroker\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQ
{
    /**
     * @var
     */
    public $channel;
    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;
    protected $response;

    /**
     * @var AMQPMessage
     */
    protected AMQPMessage $message;

    protected string $exchangeName;

    protected string $correlationId = '';

    /**
     * @var string
     */
    protected string $routeKey = '';

    /**
     * @var string
     */
    protected string $queueName = '';

    /**
     * @return RabbitMQ
     * @throws \Exception
     */
    public function setConnection(): static
    {
        $this->connection = (new Connection())->connect();
        $this->setChannel();

        return $this;
    }

    /**
     * @return void
     */
    protected function setChannel(): void
    {
        $this->channel = $this->connection->channel();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function createQueue(string $name = ''): static
    {
        // обявляем саму очередь в Канале
        list($this->queueName, ,) = $this->channel->queue_declare(
            $name,
            passive: false,
            durable: false,
            exclusive: false,
            auto_delete: false,
            nowait: false
        );

        $this->channel->queue_bind(
            $this->queueName, $this->exchangeName, $this->routeKey
        );

        return $this;
    }

    /**
     * Создаем подписчика
     * @param string $correlationId
     * @return $this
     * @todo допрописать аргументы
     *
     */
    public function createSubscriber(string $correlationId = ''): static
    {
        if (!$this->queueName) {
            $this->createQueue(); // если очередь не создана сгенерируем новый
        }

        $this->correlationId = $correlationId;

        $this->channel->basic_consume(
            $this->queueName, '', false, true, false, false, function ($message) { // создаем подписчика

            $this->response = $message->body;

            if ($message->get('correlation_id') == $this->correlationId) {
                $this->response = $message->body;
            }

        }
        );

        return $this;
    }

    /**
     * @param array $payload
     * @param array $additional
     * @return $this
     */
    public function createMessage(array $payload = [], array $additional = []): static
    {
        $payload = json_encode($payload);

        $additional['content_type'] = 'application/json';

        if ($this->correlationId) {
            $additional['correlation_id'] = $this->correlationId;
            $additional['reply_to']       = $this->queueName;
        }

        $this->message = new AMQPMessage(
            $payload,
            $additional
        );

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function publish(): static
    {
        $this->channel->basic_publish(
            $this->message, $this->exchangeName, $this->routeKey
        ); // публикуем сообщение в очередь

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function createRouteKey(string $name): static
    {
        $this->routeKey = $name;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function subscribe(): static
    {
        $this->createSubscriber();

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->close();

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @return mixed
     */
    public function response(): mixed
    {
        while (!$this->response) {
            $this->channel->wait();
        }

        return $this->response;
    }

    /**
     * @param string $name
     * @return RabbitMQ
     */
    public function createExchange(string $name = 'master'): static
    {
        $this->exchangeName = $name;

        $this->channel->exchange_declare(
            $name,
            'direct',
            false,
            false,
            false
        );

        return $this;
    }

    /**
     * @return string
     */
    public function currentQueueName(): string
    {
        return $this->queueName;
    }
}
