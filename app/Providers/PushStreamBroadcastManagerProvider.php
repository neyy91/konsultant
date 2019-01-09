<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;
use GuzzleHttp\Client;

use App\Libs\PushStreamBroadcaster;


class PushStreamBroadcastManagerProvider extends ServiceProvider
{

    /**
     * Boot.
     * @param  BroadcastManager $broadcastManager
     * @return void
     */
    public function boot(BroadcastManager $broadcastManager)
    {

        app(BroadcastManager::class)->extend('pushstream', function($app) {
            \Log::info('extend pushstream');
            $client = new Client([
                'base_url' => $app->config['broadcasting.connections.pushstream.base_url'],
                'query'    => !is_null($app->config['broadcasting.connections.pushstream.access_key']) ? [
                    'access_key' => $app->config['broadcasting.connections.pushstream.access_key']
                ] : null,
            ]);
            
            if (!is_null($app->config['broadcasting.connections.pushstream.cert'])) {
                $client->setDefaultOption('verify', $app->config['broadcasting.connections.pushstream.cert']);
            }
            return new PushStreamBroadcaster($client);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }


}
