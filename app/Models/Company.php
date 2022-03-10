<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'address', 'phone_number', 'stifin'
    ];
    
    /**
     * Get the user that owns the company.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the offices for the company.
     */
    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    /**
     * Get the positions for the company.
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the vacancies for the company.
     */
    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }

    /**
     * The tests that belong to the company.
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'company__test', 'company_id', 'test_id');
    }
}
