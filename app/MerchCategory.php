<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchCategory extends Model
{
    protected $table = 'merch_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Many-to-Many relation to MerchProducts
     */
    public function products()
    {
        return $this->belongsToMany(MerchProducts::class, 'merch_category_product', 'merch_category_id', 'merch_product_id')->withTimestamps();
    }
}
