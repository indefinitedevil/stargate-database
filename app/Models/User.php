<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserCreated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Collection $characters
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function delete()
    {
        if ($this->id . '-no-email@example.com' == $this->email) {
            return false;
        }

        if ($this->fireModelEvent('deleting') === false) {
            return false;
        }

        $this->touchOwners();

        $this->email = $this->id . '-no-email@example.com';
        $this->name = $this->id . '-deleted';
        $this->save();

        foreach ($this->characters as $character) {
            if (in_array($character->status_id, [Status::APPROVED, Status::PLAYED])) {
                $character->status_id = Status::RETIRED;
                $character->save();
            }
        }

        $this->fireModelEvent('deleted', false);

        return true;
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function isNameUnique(): bool
    {
        static $uniqueName;
        if ($uniqueName === null) {
            $uniqueName = $this->where('name', $this->name)->count() == 1;
        }
        return $uniqueName;
    }
}
