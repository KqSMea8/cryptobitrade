<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPurchas extends Model
{
    use SoftDeletes;


    protected $table = 'productspurchase';

    protected $fillable = ['user_id','address','country','state','city','zipcode','image','productname','pv','quantity','amount'] ;
}
