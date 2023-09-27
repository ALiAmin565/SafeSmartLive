<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;




class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'verified',
        'phone',
        'otp',
        'plan_id',
        'affiliate_code',
        'affiliate_link',
        'comming_afflite',
        'number_of_user',
        'Status_Plan',
        'start_plan',
        'end_plan',
        'money',
        'remember_token',
        'binanceApiKey',
        'binanceSecretKey',
        'is_bot',
        'admins',
        'num_orders',
        'open_orders',
        'orders_usdt',
        'tickers'


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function binanceloges()
    {
        return $this->hasMany(Binance::class);
    }


    public function historypayment()
    {
        return $this->hasMany(transfer_many::class);
    }




    // for recomndation

    public function recommendation()
    {
        return $this->hasMany(recommendation::class);
    }

    public function plan()
    {
        return $this->hasOne(plan::class, 'id', 'plan_id');
    }

    public function imgPay()
    {
        return $this->hasOne(Payment::class, 'user_id', 'id');
    }



    public function Role()
    {
        return $this->belongsToMany(plan::class, '_admin__role', 'user_id', 'plan_id');
    }

    //transaction_binance
    public function transaction_binance()
    {
        return $this->hasMany(ImageSubmissionBinance::class, 'user_id', 'id');
    }

    // for bot transfer
    public function bot_transfer()
    {
        return $this->hasMany(BotTransfer::class, 'user_id', 'id');
    }




    public function BuySellBinance()
    {

        return $this->hasMany(binanceUser::class, 'user_id', 'id');
    }

    public function fessBot()
    {
        return $this->hasMany(feesBot::class, 'user_id', 'id');
    }


    public function allsendandrecive()
    {
        return $this->hasMany(transactionUser::class, 'user_id', 'id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(transactionUser::class, 'recive_id', 'id');
    }
    public function DepositsBinance()
    {
        return $this->hasMany(DepositsBinance::class, 'user_id', 'id');
    }
}
