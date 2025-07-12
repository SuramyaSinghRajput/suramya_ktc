<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    // use SoftDeletes;
    protected $table = 'user_rating';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_id',
        'audio_id',
        'rating',
        'description',
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
