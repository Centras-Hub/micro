<?php


namespace Centras\Layers\Infrastructure\Logger\Graylog;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class Graylog
{
    /**
     * @var string
     */
    protected string $url = '';

    /**
     * @var array
     */
    protected array $payload = [];

    /**
     * Строем базовые поля
     */
    public function build()
    {
        $this->url             = config('centras.graylog_url');
        $this->payload['host'] = gethostname();
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

    /**
     * @return array
     */
    protected function payload(): array
    {
        return $this->payload;
    }
}
