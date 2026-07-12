<?php

namespace App\Modules\Identity\Infrastructure\Persistence\LoginHistory;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $user_id
 * @property $ip
 * @property $user_agent
 * @property $status
 * @property $created_at
 */
class LoginHistoryModel extends Model
{
    protected $table = 'identity.login_histories';

    protected $fillable = [
        'user_id',
        'ip',
        'user_agent',
        'status',
        'created_at'
    ];

    public $timestamps = false;
}
