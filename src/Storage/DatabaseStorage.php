<?php

namespace CherryneChou\LaravelShoppingCart\Storage;

use Illuminate\Support\Arr;

class DatabaseStoreage implements Storage
{
    /**
     * @var string
     */
    private $table ='';

    /**
     * @var array
     */
    private $field = ['__raw_id', 'id', 'quantity', '__model', 'type', 'status'];

    /**
     * 
     */
    public function __contruction()
    {
        $prefix = config('cart.database.prefix', '');

        $table_name = $prefix . 'cart';

        $this->table = table_name;
    }

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
        DB::table($this->table)->whereNotIn('__raw_id', $rawIds)->where('key', $key)->delete();

        $keys = explode('.', $key);

        $userId = end($keys);
        $guard = prev($keys);

        $values = $values->toArray();

        foreach ($values as $value) {
            $item = array_only($value, $this->field);
            $attr = json_encode(array_except($value, $this->field));
            $insert = array_merge($item, ['attributes' => $attr, 'key' => $key, 'guard' => $guard, 'user_id' => $userId]);
            if (DB::table($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])->first()) {
                DB::table($this->table)->where(['key' => $key, '__raw_id' => $item['__raw_id']])
                    ->update(array_except($insert, ['key', '__raw_id']));
            } else {
                DB::table($this->table)->insert($insert);
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
        $items = DB::table($this->table)->where('key', $key)->get();

        $items = $items->toArray();
        $collection = [];
        foreach ($items as $item) {
            $item = json_decode(json_encode($item), true);
            $attr = json_decode($item['attributes'], true);
            $item = array_only($item, $this->filed);
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
        DB::table($this->table)->where('key', $key)->delete();
    }

}