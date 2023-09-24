<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class botsOrders extends Model
{
    use HasFactory;

    protected $table='bots_orders'; // history bot

    protected $fillable=['bot_id','symbol','side','price','stop_price','recomondations_id','buy_price_sell','created_at'];
}
