<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchProductImage extends Model
{
    // this model now represents variant images
    protected $table = 'merch_product_variant_images';

    protected $fillable = [
        'merch_product_variant_id',
        'image_path',
        'label',
        'sort_order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function merchProductVariant()
    {
        return $this->belongsTo('App\MerchProductVariant', 'merch_product_variant_id');
    }
}
