<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tagert extends Model
{
    use HasFactory;


    protected $table="_recommindation_target";

    public $fillable=['recomondations_id','target'];

    public $hidden=['created_at','updated_at'];
}
