<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'results';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'result'
    ];
    
    /**
     * Get the user that owns the result.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the company that owns the result.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the test that owns the result.
     */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    
    /**
     * Get the packet that owns the result.
     */
    public function packet()
    {
        return $this->belongsTo(Packet::class, 'packet_id');
    }
}
