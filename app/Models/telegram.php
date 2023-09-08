<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class telegram extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = 'telgram_groups';


    public $fillable = ['merchant', 'token', 'title'];


    public function plan()
    {
        return $this->belongsToMany(plan::class, 'plan_telgram_group', 'telgram_groups_id', 'planes_id');
    }
}
