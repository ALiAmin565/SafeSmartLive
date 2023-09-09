<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositsBinance extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'textId',
        'network',
        'user_id',
        'status',
    ];
}
