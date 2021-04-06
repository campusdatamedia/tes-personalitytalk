<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posisi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posisi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_posisi';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'nama_posisi',
        'tes',
        'keahlian',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
