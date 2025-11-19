<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MerchProduct extends Model
{
    // table khusus untuk merch product (sesuai migration `merch_products`)
    protected $table = "merch_products";

    protected $fillable = [
        'user_id',
        'kategori_id',
        'karya_id',
        'title',
        'slug',
        'description',
        'asuransi',
        'status',
        'views',
        'kondisi',
    ];

    /**
     * Casts
     */
    protected $casts = [
        // no special casts required for this migration's columns
    ];

    public function kategori()
    {
        return $this->belongsTo('App\Kategori', 'kategori_id');
    }
    public function karya()
    {
        return $this->belongsTo('App\Karya', 'karya_id');
    }

    // hubungan many-to-many ke ukuran (sizes)
    public function sizes()
    {
        return $this->belongsToMany('App\Size', 'merch_product_size', 'merch_product_id', 'size_id')->withTimestamps();
    }

    /**
     * Many-to-Many relation to categories
     */
    public function categories()
    {
        return $this->belongsToMany('App\MerchCategory', 'merch_category_product', 'merch_product_id', 'merch_category_id')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // --- Accessor / Helper ---
    public function getPriceStrAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
    public function getKelipatanBidAttribute()
    {
        return 'Rp ' . number_format($this->kelipatan, 0, ',', '.');
    }
    public function getEndDateIndoAttribute()
    {
        return Carbon::parse($this->end_date)->isoFormat('dddd, D MMMM Y H:mm:s');
    }
    public function getStatusTxtAttribute()
    {
        if ($this->status == '1') {
            return '<span class="badge bg-info text-white rounded-0">PUBLISHED</span>';
        } elseif ($this->status == '2') {
            return '<span class="badge bg-danger text-white rounded-0">SOLD OUT</span>';
        } elseif ($this->status == '3') {
            return '<span class="badge bg-success text-white rounded-0">LELANG EXPIRED</span>';
        } else {
            return '<span class="badge bg-warning text-white rounded-0">DRAFT</span>';
        }
    }

    public function getFinalPriceAttribute()
    {
        if ($this->diskon > 0) {
            $diskon = $this->diskon / 100;
            $newPrice = $this->price - ($diskon * $this->price);
            return $newPrice;
        }
        return $this->price;
    }

    public function getFinalPriceStrAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    public function getHasDiskonAttribute()
    {
        return $this->diskon > 0;
    }

    public function getEndDateIsoAttribute()
    {
        return $this->end_date ? $this->end_date->toIso8601String() : null;
    }
}
