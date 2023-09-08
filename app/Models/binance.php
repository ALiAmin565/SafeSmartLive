<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class binance extends Model
{
    use HasFactory;

   
    protected $fillable=['user_id','symbol','type','side','quantity','price','stop_price','status','orderID','massageError'];
    
    
    public function user()
{

    return $this->belongsTo(user::class,'user_id','id');
}

}
