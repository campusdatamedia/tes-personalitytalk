<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends \Ajifatur\FaturHelper\Models\User
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_user', 'tanggal_lahir', 'jenis_kelamin', 'username', 'email', 'password', 'password_str', 'foto', 'role', 'has_access', 'status', 'last_visit', 'created_at',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the attribute associated with the user.
     */
    public function attribute()
    {
        return $this->hasOne(UserAttribute::class);
    }

    /**
     * Get the socmed associated with the user.
     */
    public function socmed()
    {
        return $this->hasOne(UserSocmed::class);
    }

    /**
     * Get the guardian associated with the user.
     */
    public function guardian()
    {
        return $this->hasOne(UserGuardian::class);
    }

    /**
     * Get the attachments for the packet.
     */
    public function attachments()
    {
        return $this->hasMany(UserAttachment::class);
    }
}
