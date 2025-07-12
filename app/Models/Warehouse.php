<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    // use SoftDeletes;
    protected $table = 'warehouse';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store',
        'about',
        'location',
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
    protected $casts = [];
    public function products() {
        return $this->hasMany(Product::class);
    }
    
    public function lots() {
        return $this->hasMany(Lots::class);
    }
    
}
