<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'num_order'
    ];

    /**
     * The companies that belong to the test.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company__test', 'test_id', 'company_id');
    }
}
