<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'phone_number', 'is_main'
    ];
    
    /**
     * Get the company that owns the office.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
