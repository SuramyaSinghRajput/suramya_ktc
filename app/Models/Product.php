<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // use SoftDeletes;
    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'category',
        'english_name',
        'common_names',
        'botanical_name',
        'harvest_season',
        'location_found',
        'suppliers',
        'volume',
        'track_price',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
     protected $casts = [
        'track_price' => 'boolean',
    ];
    public function lots()
    {
        return $this->hasMany(Lots::class);
    }

}
