<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StifinTest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stifin_tests';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_st';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = ['test_name', 'test_code'];
}
