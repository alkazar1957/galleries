<?php

namespace Alkazar\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
    	return $this->belongsToMany('Alkazar\Gallery\Models\User');
    }
}