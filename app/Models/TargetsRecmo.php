<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetsRecmo extends Model
{
    use HasFactory;

    protected $table="targets_recmo";

    public $fillable=['recomondations_id','target'];

    public $hidden=['created_at','updated_at'];
}