<?php

namespace Alkazar\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    
	protected $table = 'gallery';
    public function images()
    {
    	return $this->hasMany('Alkazar\Gallery\Models\GalleryImage');
    }
}
