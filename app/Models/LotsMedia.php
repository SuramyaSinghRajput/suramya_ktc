<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LotsMedia extends Model
{
    // use SoftDeletes;
    protected $table = 'lot_media';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'media_type',
        'media_type',
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
}
