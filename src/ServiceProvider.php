<?php

namespace CherryneChou\LaravelShoppingCart;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the provider.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'laravel-cart-config');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'laravel-cart-migrations');
        }


    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // merge configs
        $this->mergeConfigFrom( 
            __DIR__ . '/../config/cart.php', 'cart'
        );

        $this->app->singleton(Cart::class, function ($app) {
            
            $storage = config('cart.storage');

            $cart = new Cart(new $storage(), $app['events']);

            if (SessionStorage::class == $storage) {
                return $cart;
            }

            //The below code is used of database storage
            $currentGuard = 'default';
            $user = null;

            if ($defaultGuard = $app['auth']->getDefaultDriver()) {
                $currentGuard = $defaultGuard;
                $user = auth($currentGuard)->user();
            }

            if ($user) {
                //The cart name like `shopping_cart.{guard}.{user_id}`： shopping_cart.api.1
                $aliases = config('cart.aliases');

                if (isset($aliases[$currentGuard])) {
                    $currentGuard = $aliases[$currentGuard];
                }

                $cart->name($currentGuard . '.' . $user->id);

            } else {
                throw new Exception('Invalid auth.');
            }

            return $cart;

        });

        $this->app->alias(Cart::class, 'cart');
    }

     /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Cart::class, 'cart'];
    }
}
