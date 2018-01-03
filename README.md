# Laravel ZeroMQ

A Laravel wrapper for `ext-zmq` that exposes a `zmq` broadcast driver to publish your Laravel events via ZeroMQ.

## Requirements

- PHP 7.1
- Laravel 5.5
- ZeroMQ
- ext-zmq for PHP

## Installation

```bash
$ composer require pelim/laravel-zmq
```

The service provider is loaded automatically in Laravel 5.5 using Package Autodiscovery.

Publish vendor files to create your `config/zmq.php` file

```bash
$ php artisan vendor:publish --provider="Pelim\ZmqServiceProvider"
```

Update your `config/zmq.php` with the appropriate socket details.

Set `BROADCAST_DRIVER=zmq` in your `.env` and add the following ZeroMQ connection settings to your `config/broadcasting.php`:

```php
'connections' => [
    'zmq' => [
        'driver' => 'zmq',
    ],
]
```
