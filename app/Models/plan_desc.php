<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan_desc extends Model
{
    use HasFactory;

    public $table="plan_descs";

    public $fillable=['plan_id','desc'];


}
