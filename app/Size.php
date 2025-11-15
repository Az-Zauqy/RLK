<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';
    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    /**
     * Many-to-many relationship with MerchProducts
     */
    public function merchProducts()
    {
        return $this->belongsToMany(MerchProducts::class, 'merch_product_size', 'size_id', 'merch_product_id')->withTimestamps();
    }
}
