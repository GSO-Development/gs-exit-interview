<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'label'];

    /**
     * Get a setting value by key, with an optional default fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $val = static::where('key', $key)->value('value');

        return ($val !== null && $val !== '') ? $val : $default;
    }

    /**
     * Set (upsert) a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
