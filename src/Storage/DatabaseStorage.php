<?php

namespace CherryneChou\LaravelShoppingCart\Storage;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use CherryneChou\LaravelShoppingCart\Item;
use Illuminate\Support\Carbon;
use CherryneChou\LaravelShoppingCart\Models\Cart;

class DatabaseStorage implements Storage
{
    /**
     * @var array
     */
    private $field = ['__raw_id', 'id', 'qty' ,'__model','type', 'is_selected'];

    /**
     * @param $key
     * @param $values
     */
    public function set($key, $values)
    {
        if (is_null($values)) {
            $this->forget($key);

            return;
        }   

        $rawIds = $values->pluck('__raw_id')->toArray();

        //Delete the data that has been removed from cart.
        Cart::whereNotIn('__raw_id', $rawIds)->where('key', $key)->delete();

        $keys = explode('.', $key);

        $userId = end($keys);
        $guard = prev($keys);

        $values = $values->toArray();

        foreach ($values as $value) {
            $item = Arr::only($value, $this->field);
            $attr = json_encode(Arr::except($value, $this->field));

            $insert = array_merge($item, [
                'attributes' => $attr,
                'key' => $key,
                'guard' => $guard,
                'user_id' => $userId,
                'created_at' => Carbon::now()
            ]);

            if (Cart::where(['key' => $key, '__raw_id' => $item['__raw_id']])->first()) {
                Cart::where(['key' => $key, '__raw_id' => $item['__raw_id']])
                    ->update(Arr::except($insert, ['key', '__raw_id','created_at']));
            } else {
                Cart::insert($insert);
            }
        }
    }

    
    /**
     * @param $key
     * @param null $default
     *
     * @return Collection
     */
    public function get($key, $default = null)
    {
        $items = Cart::where('key', $key)->get();

        $items = $items->toArray();
        $collection = [];
        foreach ($items as $item) {
            $item = json_decode(json_encode($item), true);
            $attr = json_decode($item['attributes'], true);
            $item = Arr::only($item, $this->field);
            $item = array_merge($item, $attr);
            $collection[$item['__raw_id']] = new Item($item);
        }

        return new Collection($collection);
    }

    /**
     * @param $key
     */
    public function forget($key)
    {
        Cart::where('key', $key)->delete();
    }

}