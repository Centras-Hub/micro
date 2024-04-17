<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class Graylog
{
    /**
     * @var array
     */
    protected array $payload = [];

    /**
     * Строем базовые поля
     */
    public function build()
    {
        $this->payload['host'] = gethostname();
    }

    /**
     * @return void
     */
    public function send(): void
    {
        $logData = [
            'title' => $this->payload['title'],
            'payload' => $this->payload['payload'],
            'global_id' => $this->payload['global_id']
        ];

        \Log::build(['driver' => 'single', 'path' => storage_path('logs/io-log/laravel-io-'.date("Y-m-d").'.log')])
            ->info(json_encode($logData));
    }

    /**
     * @return array
     */
    protected function payload(): array
    {
        return $this->payload;
    }
}
