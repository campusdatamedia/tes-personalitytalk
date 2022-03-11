<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'positions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
    /**
     * Get the company that owns the position.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the role that owns the position.
     */
    public function role()
    {
        return $this->belongsTo(\Ajifatur\FaturHelper\Models\Role::class, 'role_id');
    }

    /**
     * The tests that belong to the position.
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'position__test', 'position_id', 'test_id');
    }

    /**
     * The skills that belong to the position.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'position__skill', 'position_id', 'skill_id');
    }
}
