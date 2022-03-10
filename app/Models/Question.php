<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number', 'description'
    ];
    
    /**
     * Get the packet that owns the question.
     */
    public function packet()
    {
        return $this->belongsTo(Packet::class, 'packet_id');
    }
}
