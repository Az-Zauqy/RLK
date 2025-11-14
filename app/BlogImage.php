<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $table = 'blog_images';
    protected $fillable = ['post_id', 'filename'];

    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id');
    }
}
