<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill_detail extends Model
{
    //
    protected $table = 'bill_details';
    protected $primaryKey = 'billDetail_id';
    protected $fillable = [
        'bill_id',
        'product_id',
        'quantity',
        'price'
    ];

}
