<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransactionLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_sender',
        'id_user_receiver',
        'amount',
        'status',
    ];

    protected $table = 'history_transaction_logs';
}
