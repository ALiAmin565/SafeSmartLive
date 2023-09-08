<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [

            'user_id',
            'plan_id',
            'image_payment',
            'status',
            'transaction_id',

    ];

    protected $hidden = [
            'updated_at',
            'created_at'

    ];
    public function plan()
    {

        return $this->belongsTo(plan::class);

    }
    public function user()
    {

        return $this->belongsTo(User::class);

    }



}
