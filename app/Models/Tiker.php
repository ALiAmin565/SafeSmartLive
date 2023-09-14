<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiker extends Model
{
    use HasFactory;

    public $table='mytickers';

    // protected $primaryKey ='ticker';

    
    public $fillable=['ticker','price'];

    public $timestamps = false;

}
