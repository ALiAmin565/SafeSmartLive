<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCreation extends Model
{
    use HasFactory;

    protected $fillable = ['affiliate_code', 'affiliate_link', 'name', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
