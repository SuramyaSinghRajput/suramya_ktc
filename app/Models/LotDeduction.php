<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LotDeduction extends Model
{
    // use SoftDeletes;
    protected $table = 'lot_deduction';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'gate_pass',
        'deduction_date',
        'qty_bag',
        'qty_kgs',
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
    // In Deduction.php
    public function lot()
    {
        return $this->belongsTo(Lots::class, 'lot_id');
    }

}
