<?php

namespace CherryneChou\LaravelShoppingCart\Storage;

/**
 * Interface Storage.
 */
interface Storage
{
    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function set($key, $value);

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function forget($key);
}