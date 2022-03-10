<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stifin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stifins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];
    
    /**
     * Get the company that owns the stifin.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the type that owns the stifin.
     */
    public function type()
    {
        return $this->belongsTo(StifinType::class, 'type_id');
    }
    
    /**
     * Get the aim that owns the stifin.
     */
    public function aim()
    {
        return $this->belongsTo(StifinAim::class, 'aim_id');
    }
}
