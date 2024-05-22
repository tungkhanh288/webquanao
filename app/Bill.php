<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $table = 'bills';
    protected $primaryKey = 'bill_id';
    protected $fillable = [
        'customer_id',
        'user_id',
        'bill_date',
        'bill_total',
        'bill_status'
    ];



    public function bill_details(): HasMany
    {
        return $this->hasMany(Bill_detail::class, 'bill_id', 'bill_id');
    }

}
