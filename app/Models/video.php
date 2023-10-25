<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class video extends Model
{
    use HasFactory;
    protected $table='videos';

    public $fillable=[
        'title',
        'img',
        'desc',
        'video',
        'video_link',
        'created_at'
    ];
}
