<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AppUsers extends Model
{
    // use SoftDeletes;
    protected $table = 'app_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'social_id',
        'name',
        'status',
        'profile_image',
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
