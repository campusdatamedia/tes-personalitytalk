<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seleksi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seleksi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_seleksi';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_pelamar',
		'id_lowongan',
		'hasil',
		'waktu_wawancara',
		'tempat_wawancara',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
