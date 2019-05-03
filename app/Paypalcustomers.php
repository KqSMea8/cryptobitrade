<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paypalcustomers extends Model
{
    
    use SoftDeletes;

    protected $table = 'paypal_customers';


    protected $fillable = ['data','paypal_data','paypal_payment_responce','paypal_recurring_responce','status'];
}
