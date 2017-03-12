<?php

namespace Alkazar\Gallery\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *---------------------------------------------------------------
     *  A user may have more than one role
     *---------------------------------------------------------------
     */
    public function roles()
    {
        return $this->belongsToMany('Alkazar\Gallery\Models\Role');
    }

    /**
     *---------------------------------------------------------------
     *  Check in the array of user roles
     *  @param array
     *  @return bool
     *---------------------------------------------------------------
     */
    public function hasAnyRole($roles)
    {
        if (is_array( $roles )) {
            foreach ( $roles as $role ) {
                if ( $this->hasRole($role) ) {
                    return true;
                }
            }
        } else {
            if ( $this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     *---------------------------------------------------------------
     *  Check if the user has the role passed from hasAnyRole($roles)
     *  @param string
     *  @return bool
     *---------------------------------------------------------------
     */
    public function hasRole ( $role )
    {
        if ( $this->roles()->where('role', $role)->first() ) {
            return true;
        }
    return false;
    }

}
