<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MerchProducts extends Model
{
    // table khusus untuk merch product
    protected $table = "merch_product";

    protected $fillable = [
        'id',
        'user_id',
        'kategori_id',
        'karya_id',
        'title',
        'slug',
        'description',
        'price',
        'diskon',
        'stock',
        'sku',
        'weight',
        'asuransi',
        'long',
        'height',
        'width',
        'status',
        'views',
        'kondisi',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'end_date' => 'datetime',
        'size' => 'array',
    ];

    // --- Relasi ---
    public function images()
    {
        return $this->hasMany('App\MerchProductImage', 'merch_product_id');
    }

    public function imageUtama()
    {
        return $this->hasOne('App\MerchProductImage', 'merch_product_id')->where('name', '=', 'img_utama');
    }
    public function imageDepan()
    {
        return $this->hasOne('App\MerchProductImage', 'merch_product_id')->where('name', '=', 'img_depan');
    }
    public function imageSamping()
    {
        return $this->hasOne('App\MerchProductImage', 'merch_product_id')->where('name', '=', 'img_samping');
    }
    public function imageAtas()
    {
        return $this->hasOne('App\MerchProductImage', 'merch_product_id')->where('name', '=', 'img_atas');
    }

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

    public function bid()
    {
        return $this->hasMany(Bid::class);
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
