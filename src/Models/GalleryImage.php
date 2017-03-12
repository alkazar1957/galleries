<?php

namespace Alkazar\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['gallery_id', 'file_name', 'file_size', 'file_mime', 'file_path', 'created_by'];

    public function gallery()
    {
    	return $this->belongsTo('Alkazar\Gallery\Models\Gallery');
    }
}
