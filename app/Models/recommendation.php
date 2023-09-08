<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class recommendation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table='recomondations';

    protected $fillable=[
        'title',
        'desc',
        'entry_price',
        'stop_price',
        'currency',
        'img',
        'archive',
        'active',
        'number_show',
        'user_id',
        'planes_id',
        'created_at'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {

        return $this->belongsToMany(plan::class);

    }
    public function plan2()
    {
       return $this->belongsTo(plan::class,'planes_id','id');
    }

    public function archive()
    {
        return $this->hasMany(Archive::class);
    }

     public function target()
     {
        return $this->hasMany(tagert::class,'recomondations_id','id');

     }
     
      public function tragetsRecmo()
     {
        return $this->hasMany(TargetsRecmo::class,'recomondations_id','id');

     }

     public function Recommindation_Plan()
     {
        return $this->hasMany(plan_recommendation::class,'recomondations_id','id');
     }
     
       public function ViewsRecomenditionnumber()
     {
        return $this->hasMany(ViewsRecomendition::class,'recomenditions_id','id');
     }

}


