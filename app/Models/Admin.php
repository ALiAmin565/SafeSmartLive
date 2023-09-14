<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table="_admin__role";
    protected $fillable=['user_id','plan_id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
