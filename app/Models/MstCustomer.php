<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MstCustomer extends Model
{
    use HasFactory;
    protected $table = "mst_customers";

    protected $fillable = [
        'customer_name',
        'email',
        'address',
        'tel_num',
        'is_active'
    ];

}
