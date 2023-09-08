<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massage_media extends Model
{
    use HasFactory;

    public $fillable=['img','video','audio'];
    
      public function Massage()
    {
        return $this->belongsTo(Massage::class, 'massage_id', 'id');
    }


   }
