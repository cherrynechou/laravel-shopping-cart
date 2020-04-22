<?php

namespace CherryneChou\LaravelShoppingCart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for Laravel.
 */
class Cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
