<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

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
     * @return void
     */
    public function send(): void
    {
        try {
            $response = Http::acceptJson()->post(
                $this->url,
                $this->payload
            );

            if ($response->failed()) {
                \Log::error('Graylog service error, see the log in service: ', [$response->body()]);
            }
        } catch (ConnectionException $exception) {
            \Log::error('connection error in Graylog service: ' . $exception->getMessage());
        } catch (\Exception $exception) {
            \Log::error('Undefined Graylog service error: ' . $exception->getMessage());
        }
    }
}
