<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchProductImage extends Model
{
    protected $table = 'merch_product_image';

    protected $fillable = [
        'merch_product_id',
        'name',
        'path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function merchProduct()
    {
        return $this->belongsTo('App\MerchProducts','merch_product_id');
    }
}
