<?php


namespace Centras;

use Centras\Layers\Infrastructure\Logger\Graylog\IO;
use Illuminate\Support\ServiceProvider;

class CentrasServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $payload = request()->all();

        $this->app->singleton('IOLog', function () use ($payload) {
            $ioLog = new IO();

            $ioLog->build();

            if(isset($payload['global_id'])){
                $ioLog->setGlobalId($payload['global_id']);
            }

            if(isset($payload['partner_id'])){
                $ioLog->setPartnerId($payload['partner_id']);
            }

            return $ioLog;
        });
//        @todo пока пускай будут
//        request()->request->remove('partner_id');
//        request()->request->remove('global_id');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/centras.php' => config_path('centras.php'),
        ]);
    }
}
