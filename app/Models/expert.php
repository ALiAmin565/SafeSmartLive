<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expert extends Model
{
    use HasFactory;

    

    protected $fillable = ['admin', 'buy_price', 'users', 'status', 'stoplose', 'targets', 'ticker', 'entry', 'tradeinfo', 'recomondations_id', 'bot_num', 'last_tp'];

    public function scopeWhen($query, $condition, $value)
    {
        if ($condition) {
            return $query->where('last_tp', $value);
        }

        return $query;
    }
}
