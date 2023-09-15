<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'recive_id', 'amount', 'transaction_id', 'name'];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'recive_id', 'id');
    }
}
