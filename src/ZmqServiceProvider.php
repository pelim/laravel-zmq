<?php

namespace Pelim\LaravelZmq;

use Illuminate\Support\ServiceProvider;
use Pelim\LaravelZmq\Broadcasting\Broadcaster\ZmqBroadcaster;

/**
 * Class ZmqServiceProvider
 * @package Pelim\LaravelZmq
 */

class ZmqServiceProvider extends ServiceProvider {
	
	public function boot() {

		$this->publishes([__DIR__ .'/../config/zmq.php' => config_path('zmq.php')]);

		$this->app->make('Illuminate\Contracts\Broadcasting\Factory')->extend('zmq', function ($app) {
				return new ZmqBroadcaster($this->app['zmq'],
					array_get($app['config'], 'broadcasting.connections.zmq.connection')
				);
			});
	}
	
	public function register() {
		$this->app->singleton('zmq', function ($app) {
				return new Zmq(config('zmq.connections'));
			});
	}

	/**
	 * @return array
	 */
	public function provides() {
		return ['zmq'];
	}
}