<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotStatus extends Model
{
    use HasFactory;

    protected $fillable = [
       'is_active',
    ];

    protected $table = 'bot_controller';
}
