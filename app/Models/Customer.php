<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    const SELECT_RAW = 'name,address,mobile,CONCAT(ST_X(`location`),",",ST_Y(`location`)) AS location';
    protected $fillable = [
        'name',
        'location',
        'address',
        'mobile'
    ];
}
