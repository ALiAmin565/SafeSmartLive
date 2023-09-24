<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bot extends Model
{
    use HasFactory;

    protected $table='bots';

    protected $fillable=['bot_name','desc'];

    protected $hidden=['created_at','updated_at'];


    public function bot_order()
    {
        return $this->hasMany(botsOrders::class);
    }
}
