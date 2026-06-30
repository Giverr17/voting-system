<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Request-scoped memoization so repeated reads don't hit the DB every time.
    protected static array $memo = [];

    /**
     * Read a setting value, falling back to $default when it isn't set.
     */
    public static function get(string $key, $default = null)
    {
        if (!array_key_exists($key, static::$memo)) {
            static::$memo[$key] = static::query()->where('key', $key)->value('value');
        }

        return static::$memo[$key] ?? $default;
    }

    /**
     * Create or update a setting value.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$memo[$key] = $value;
    }

    // --- Convenience helpers for the two election controls ---

    public static function isElectionOpen(): bool
    {
        return static::get('election_status', config('election.status')) === 'open';
    }

    public static function isRegistrationOpen(): bool
    {
        return static::get('registration_status', 'open') === 'open';
    }
}
