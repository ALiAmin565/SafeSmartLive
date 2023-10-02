<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class binance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'symbol', 'type', 'side', 'quantity', 'price', 'stop_price', 'status', 'orderID', 'massageError', 'recomondations_id', 'respone', 'buy_price_sell', 'bot_num', 'profit_usdt', 'profit_per', 'fees', 'status_fees'];


    public function user()
    {

        return $this->belongsTo(user::class, 'user_id', 'id');
    }

}
