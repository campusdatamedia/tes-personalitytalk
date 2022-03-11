<?php

namespace App\Models;

class UserAttribute extends \Ajifatur\FaturHelper\Models\UserAttribute
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'office_id', 'position_id', 'vacancy_id', 'birthdate', 'birthplace', 'gender', 'country_code', 'dial_code', 'phone_number', 'address', 'identity_number', 'religion', 'relationship', 'latest_education', 'job_experience', 'start_date', 'end_date'
    ];
    
    /**
     * Get the company that owns the user attribute.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    /**
     * Get the office that owns the user attribute.
     */
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
    
    /**
     * Get the position that owns the user attribute.
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
    
    /**
     * Get the vacancy that owns the user attribute.
     */
    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }
}
