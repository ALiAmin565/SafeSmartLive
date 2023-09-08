<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPlans extends Model
{
    use HasFactory;

    protected $table = 'notification_plans';

    protected $fillable = [
        'text',
        'user_id',
        'plan_id',
    ];

}
