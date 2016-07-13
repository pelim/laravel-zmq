<?php

namespace Pelim\LaravelZmq;

use Illuminate\Support\ServiceProvider;
use Pelim\LaravelZmq\Broadcasting\Broadcaster\ZmqBroadcaster;

/**
 * Class ZmqServiceProvider
 * @package Pelim\LaravelZmq
 */
class ZmqServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__  . '../config/zmq.php' => config_path('zmq.php'),
        ]);
    }
    
    public function register()
    {
        $this->app->singleton('zmq', function ($app) {
            return new Zmq($app['config']['zmq.connections']);
        });
        
        $this->app->make('\Illuminate\Broadcasting\BroadcastManager')->extend('zmg', function($config) {
            new ZmqBroadcaster($this->app['Pelim\LaravelZmq\Zmq'],  array_get($config, 'connection'));
        });
    }
    
    public function publishes()
    {
        return [
            'zmq'
        ];
    }
}