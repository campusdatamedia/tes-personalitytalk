<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'soal';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_soal';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_paket',
		'nomor',
		'soal'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
