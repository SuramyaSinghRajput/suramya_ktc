<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lots extends Model
{
    // use SoftDeletes;
    protected $table = 'lots';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'lot_number',
        'grade',
        'item',
        'labour_rate',
        'warehouse_rate',
        'product_rate',
        'quantity_bags',
        'quantity_kgs',
        'remaining_quantity_bags_after_deduction',
        'date',
        'status',
        'bill_number',
        'bill_date',
        'packaging_remark',
        'each_bag_weight',
        'pay_by',
        'clear_date',
        'quality_description',
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
