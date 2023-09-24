<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bots_usdt extends Model
{
    use HasFactory;

    protected $table='bots_usdt';

      protected $fillable=['user_id','bot_id','orders_usdt','bot_status'];

      public function bot()
      {
        return $this->belongsTo(bot::class);
      }

}
