<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massage extends Model
{
    use HasFactory;
    public $fillable=['user_id','plan_id','massage'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function media()
    {
        return $this->hasOne(Massage_media::class);

    }
    
        public function MassageMedia()
    {
        return $this->hasMany(Massage_media::class,'massage_id','id');
    }
}
