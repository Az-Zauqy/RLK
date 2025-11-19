<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchProductVariantSize extends Model
{
    protected $table = 'merch_product_variant_sizes';

    protected $fillable = [
        'merch_product_variant_id',
        'size',
        'stock',
        'diskon',
        'price',
        'weight',
        'long',
        'width',
        'height',
        'sku',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'diskon' => 'decimal:2',
        'weight' => 'decimal:2',
        'long' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Belongs to a product variant
     */
    public function variant()
    {
        return $this->belongsTo(MerchProductVariant::class, 'merch_product_variant_id');
    }

    /**
     * Helper: final price after discount
     */
    public function getFinalPriceAttribute()
    {
        if ($this->diskon > 0) {
            $diskon = $this->diskon / 100;
            return $this->price - ($diskon * $this->price);
        }
        return $this->price;
    }
}
