<?php


namespace Centras;

use Centras\Layers\Infrastructure\Logger\Graylog\IO;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class CentrasServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $payload = $request->all();

        $this->app->singleton('IOLog', function () use ($payload) {
            $ioLog = new IO();

            $ioLog->build();
            $ioLog->setPartnerId($payload['partner_id']);
            $ioLog->setGlobalId($payload['global_id']);

            return $ioLog;
        });
    }
}
