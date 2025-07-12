<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    // use SoftDeletes;
    protected $table = 'bills';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'warehouse_id', 'bill_number', 'bill_date', 'clear_date',
        'payment_method', 'remark', 'lot_id', 'bill_file',
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

    public function media()
    {
        return $this->hasMany(\App\Models\LotsMedia::class, 'lot_id');
    }
    // App\Models\Lots.php

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}
