<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];
    
    /**
     * Get the test that owns the description.
     */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    
    /**
     * Get the packet that owns the description.
     */
    public function packet()
    {
        return $this->belongsTo(Packet::class, 'packet_id');
    }
}
