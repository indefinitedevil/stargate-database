<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\UserCreated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int id
 * @property string name
 * @property string pronouns
 * @property string membership_name
 * @property string membership_number
 * @property string email
 * @property Character[]|Collection characters
 * @property Membership[]|Collection memberships
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    const PLOT_CO_ID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'membership_name',
        'email',
        'password',
        'pronouns',
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

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withPivot('character_id', 'attended', 'role');
    }

    public function isNameUnique(): bool
    {
        static $uniqueName;
        if ($uniqueName === null) {
            $uniqueName = $this->where('name', $this->name)->count() == 1;
        }
        return $uniqueName;
    }

    public function getCharacter($characterId): ?Character
    {
        static $characters = [];
        if (!isset($characters[$characterId])) {
            $characters[$characterId] = $this->characters()->find($characterId);
        }
        return $characters[$characterId] ?? null;
    }

    public function getViewRoute(): string
    {
        return route('profile.view', ['userId' => $this, 'userName' => Str::slug($this->name)]);
    }

    public function getMembershipNameAttribute(): string
    {
        if (!empty($this->attributes['membership_name'])) {
            return $this->attributes['membership_name'];
        }
        return $this->name;
    }

    public function getMembershipNumberAttribute(): string
    {
        $name = $this->membership_name;
        $nameTokens = preg_split('/\s+/', $name);
        $initials = '';
        foreach ($nameTokens as $token) {
            $initials .= strtoupper($token[0]);
        }
        $membershipNumber = 'SG-' . str_pad($this->id, 4, '0', STR_PAD_LEFT) . '-' . $initials;
        $membership = $this->memberships?->first();
        if ($membership) {
            $membershipNumber .= '-' . $membership->name;
        }
        return $membershipNumber;
    }

    public function getMembershipStatusAttribute(): string
    {
        $membership = $this->memberships?->first();
        if ($membership instanceof Membership) {
            if ($membership->isActive()) {
                return __('Active member');
            }
            return __('Lapsed member');
        }
        return __('No membership found');
    }

    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class)
            ->orderBy('start_date', 'desc');
    }
}
