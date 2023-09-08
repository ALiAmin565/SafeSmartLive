<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewsRecomendition extends Model
{
    use HasFactory;

    protected $table='views_recomenditions';

    public $fillable=[
        'user_id',
        'recomenditions_id',
    ];

}
