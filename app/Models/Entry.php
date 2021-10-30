<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property string entry_key
 * @property string value
 * @property Carbon created_at
 */
class Entry extends Model
{
    protected $fillable = [
        'entry_key',
        'value',
    ];

    public $timestamps = false;

    public function getDates()
    {
        return ['created_at'];
    }

    public static $rules = [
        'entry_key' => 'required|max:255',
        'value' => 'nullable|max:2000',
    ];

    public static $messages = [
        'entry_key.required' => 'Key is required',
        'entry_key.max' => 'Maximum key length is 255 characters',
        'value.max' => 'Maximum value length is 2000 characters',
    ];

    /**
     * @param string $key
     * @param int|null $timestamp
     * @return mixed
     */
    public static function showValue($key, $timestamp = null)
    {
        $carbonTime = self::getCarbonTimeFromTimestampOrNow($timestamp);
        return self::getEntryValueAtTimestamp($key, $carbonTime);
    }

    public static function isValidTimestamp($timestamp = null)
    {
        return $timestamp !== null && is_numeric($timestamp) && (int)$timestamp==$timestamp;
    }

    public static function getCarbonTimeFromTimestampOrNow($timestamp = null)
    {
        return self::isValidTimestamp($timestamp) ? Carbon::createFromTimestamp($timestamp) : Carbon::now();
    }

    /**
     * @param $key
     * @param $carbonTime
     * @return mixed
     */
    public static function getEntryValueAtTimestamp($key, $carbonTime)
    {
        $query = Entry::where('entry_key', $key)
            ->where('created_at', '<=', $carbonTime)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->select('value');
        return $query->firstOr(function () {
            return [
                'success' => false,
                'message' => 'Key not found',
            ];
        });
    }
}
