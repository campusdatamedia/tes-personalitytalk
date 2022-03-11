<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exam_time', 'memorizing_time', 'is_auth', 'access_token'
    ];
    
    /**
     * Get the company that owns the test setting.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the packet that owns the test setting.
     */
    public function packet()
    {
        return $this->belongsTo(Packet::class, 'packet_id');
    }
}
