<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use HasFactory;
    // use SoftDeletes;
    public $table='archives';

    protected $fillable=['title','desc','recomondation_id','user_id','created_at'];



    protected $hidden = [

        'updated_at',
    ];





    public function recommendation()
    {

       return $this->belongsTo(recommendation::class,'recomondation_id','id');


    }



    public function user()
    {

        return $this->belongsTo(user::class,'user_id','id');
    }

}
