<?php

namespace HustleWorks\ChuteLaravel;

use Illuminate\Support\ServiceProvider;

/**
 * ChuteServiceProvider
 *
 * @category Package
 * @package  HustleWorks\ChuteLaravel
 * @author   Don Herre <don@hustleworks.com>
 * @license  MIT
 * @link     http://hustleworks.com
 */
class ChuteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Published items
        $this->publishes([
            __DIR__ . '/../resources/config/chute.php' => config_path('chute.php'),
        ], 'config');

        // Commands
        // ??
        // ??

        // Routes
//        include __DIR__.'/../resources/routes.php';

        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../resources/migrations/');
    }
}
