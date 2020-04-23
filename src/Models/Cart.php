<?php

namespace CherryneChou\LaravelShoppingCart\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('cart.database.connection') ?: config('database.default');
        $this->setConnection($connection);
        $this->setTable(config('cart.database.prefix', '') . 'carts');
        parent::__construct($attributes);
    }


}