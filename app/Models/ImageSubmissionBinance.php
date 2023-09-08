<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSubmissionBinance extends Model
{
    use HasFactory;

    protected $table = 'transaction_binance';

    protected $fillable = [
        'user_id',
        'image',
        'status',
        'price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }
}
