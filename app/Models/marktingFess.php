<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class marktingFess extends Model
{
    use HasFactory;

      public $table = 'markting_fesses';
      
      protected $fillable = [
        'user_id', // Add 'user_id' to the $fillable array
        'markting_id',
        'amount',
        'status',
        'profit_users',
        'Generations',
        'bot_id',
        'plan_id'
    ];
}
