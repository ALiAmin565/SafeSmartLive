<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class binanceUser extends Model
{
    use HasFactory;
 
    protected $table='binance_users';
    protected $fillable=['user_id','symbol','side','quantity','price','status','orderID','massageError','recomondations_id'];


    
}
