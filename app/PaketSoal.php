<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaketSoal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paket_soal';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_paket';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_tes',
        'nama_paket',
		'jumlah_soal',
		'status',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
