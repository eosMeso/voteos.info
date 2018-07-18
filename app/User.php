<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Generate user model from EOS account.
     *
     * @param \EOSPHP\EOSClient $eos
     * @param string            $account
     *
     * @return User
     */
    public static function factory(\EOSPHP\EOSClient $eos, $account)
    {

        $account     = $eos->chain()->getAccount($account);

        $user = self::where('name', $account->account_name)->first();
        if (!$user) {
            $user = new User();
            $user->name = $account->account_name;
        }
        $user->stake = ($account->net_weight + $account->cpu_weight) / (10 * 1000);
        $user->save();

        return $user;
    }
}
