<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class plan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'planes';


    protected $fillable = [

        'name',
        'nameChannel',
        'discount',
        'price',
        'percentage1',
        'percentage2',
        'percentage3',
        'number_bot',
        'created_at'
    ];

    protected $casts = [
        'percentage' => 'string',
    ];

    public function recommendation()
    {
        return $this->belongsToMany(recommendation::class);
    }

    public function telegram()
    {
        return $this->belongsToMany(telegram::class, 'plan_telgram_group', 'planes_id', 'telgram_groups_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan_desc()
    {
        return $this->hasMany(plan_desc::class, 'plan_id', 'id');
    }
}
