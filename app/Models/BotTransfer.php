<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_money',
        'image',
        'status',
        'transaction_id',
        'user_id',
    ];

    protected $table = 'bot_transfer';

    public function userBotTransfer()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }
}
