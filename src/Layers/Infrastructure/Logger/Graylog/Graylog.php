<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Facades\Http;

class Graylog
{
    /**
     * @var string
     */
    protected string $url = 'http://graylog/api/write/log';

    /**
     * @var array
     */
    protected array $payload = [];

    /**
     * @return array
     */
    protected function payload(): array
    {
        return $this->payload;
    }

    /**
     *
     */
    public function send()
    {
        return Http::acceptJson()->post(
            $this->url
        );
    }
}
