<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'section',
        'description'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    public static function getValue($key, $default = '')
    {
        $content = static::where('key', $key)->first();
        return $content ? $content->value : $default;
    }

    public static function getJsonValue($key, $default = [])
    {
        $content = static::where('key', $key)->where('type', 'json')->first();
        return $content ? json_decode($content->value, true) : $default;
    }

    public static function setValue($key, $value, $type = 'text', $section = null, $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $type === 'json' ? json_encode($value) : $value,
                'type' => $type,
                'section' => $section,
                'description' => $description
            ]
        );
    }
}
