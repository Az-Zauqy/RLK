<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchProductVariant extends Model
{
    protected $table = 'merch_product_variants';

    protected $fillable = [
        'merch_product_id',
        'name',
        'sku',
        'price',
        'diskon',
        'stock',
        'size',
        'weight',
        'long',
        'width',
        'height',
    ];

    protected $casts = [
        'size' => 'array',
        'price' => 'decimal:2',
        'diskon' => 'decimal:2',
        'weight' => 'decimal:2',
        'long' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Many-to-one: this variant belongs to a MerchProduct
     */
    public function product()
    {
        return $this->belongsTo(MerchProduct::class, 'merch_product_id');
    }

    /**
     * Variant images
     */
    public function images()
    {
        return $this->hasMany('App\MerchProductImage', 'merch_product_variant_id');
    }

    /**
     * Variant sizes (detailed size rows)
     */
    public function variantSizes()
    {
        return $this->hasMany(MerchProductVariantSize::class, 'merch_product_variant_id');
    }
}
