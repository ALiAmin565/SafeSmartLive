<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan_recommendation extends Model
{
    use HasFactory;

    public $table='recommendation__plans';

    public $fillable=['planes_id','recomondations_id'];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'planes_id', 'id');

    }
}
