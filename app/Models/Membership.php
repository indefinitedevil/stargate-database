<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Membership
 *
 * @property int $id
 * @property string $name
 * @property string $membership_number
 * @property string $start_date
 * @property string $end_date
 * @property \Illuminate\Support\Collection|User[] $users
 */
class Membership extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public static function matchMember(string $number): ?User
    {
        $numberTokens = explode('-', $number);
        if (count($numberTokens) > 2 && is_numeric($numberTokens[1])) {
            $user = User::find($numberTokens[1]);
            if ($user && $user->membership_number === $number) {
                return $user;
            }
        }
        return null;
    }

    public function isActive(): bool
    {
        return date('Y-m-d') <= $this->end_date;
    }
}
