<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feesBot extends Model
{
    use HasFactory;

    protected $table='fees_bots';

    protected $fillable=['user_id ','fees','number_bot','ticker','profusdt','status'];
}
