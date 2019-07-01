<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableContract;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;
use Cog\Contracts\Love\Liker\Models\Liker as LikerContract;
use Cog\Laravel\Love\Liker\Models\Traits\Liker;

class User extends Authenticatable implements JWTSubject //,ReacterableContract,LikerContract
{

    use Notifiable;
    use Favoriteability;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','email', 'password','provider', 'facebook_id', 'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function authorizeRoles($roles)

    {

        if (is_array($roles)) {

            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');

        }

        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');

    }

    /**

     * Check multiple roles

     * @param array $roles

     */

    public function hasAnyRole($roles)

    {

        return null !== $this->roles()->whereIn("name", $roles)->first();

    }

    /**

     * Check one role

     * @param string $role

     */

    public function hasRole($role)

    {

        return null !== $this->roles()->where("name", $role)->first();

    }


    public function likedPosts()
    {
        return $this->morphedByMany('App\Plan', 'like')->whereDeletedAt(null);
    }
    public function favouritePlans()
    {
        return $this->morphedByMany('App\Plan', 'favouriteable')
            ->withPivot(['created_at'])
            ->orderBy('pivot_created_at', 'desc');
    }

}