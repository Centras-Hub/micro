<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Facades\Http;

class Graylog
{
    /**
     * @var string
     */
    protected string $url = 'http://logger:8000/api/write/log';

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
        $response = Http::acceptJson()->post(
            $this->url,
            $this->payload
        );

        if ($response->failed()) {

        }
    }
}
