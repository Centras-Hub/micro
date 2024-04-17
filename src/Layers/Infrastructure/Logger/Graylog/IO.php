<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Str;

class IO extends Graylog
{
    /**
     * в данном массиве будет храниться уникальный ID каждого запроса
     *
     * @var array
     */
    protected array $requestIDS = [];

    /**
     * Тело запроса Payload - а для запросов в GRAYLOG
     *
     * @var array
     */
    protected array $payload
        = [
            "version"    => "1.1",
            "title"      => null,
            "partner_id" => '0',
            "global_id"  => '0',
            "request_id" => null,
            "payment_id" => null,
            "product_id" => null,
            "payload"    => []
        ];

    /**
     * @param string $id
     * @return IO
     */
    public function setGlobalId(string $id): static
    {
        $this->payload['global_id'] = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGlobalId(): mixed
    {
        return $this->payload['global_id'];
    }

    /**
     * @param string $id
     * @return IO
     */
    public function setPartnerId(string $id): static
    {
        $this->payload['partner_id'] = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartnerId(): mixed
    {
        return $this->payload['partner_id'];
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setPaymentId(string $id): static
    {
        $this->payload['payment_id'] = $id;

        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setProductId(string $id): static
    {
        $this->payload['product_id'] = $id;

        return $this;
    }

    /**
     *
     */
    public function request(array $data): void
    {
        $this->payload['title'] = 'request from microservice: ' . gethostname();
        $this->payload['request_id'] = $this->generateRequestId();
        $this->payload['payload']    = $data;

        $this->send();
    }

    /**
     *
     */
    protected function generateRequestId(): string
    {
        $requestId = 'req_' . Str::uuid();

        $this->requestIDS[] = $requestId;

        return $requestId;
    }

    /**
     * @param array $data
     */
    public function response(array $data): void
    {
        $this->payload['title'] = 'response from microservice: ' . gethostname();
        $this->payload['request_id'] = $this->getLastId();
        $this->payload['payload']    = $data;

        $this->send();
    }

    /**
     * @return string
     */
    protected function getLastId(): string
    {
        return array_pop($this->requestIDS);
    }
}
