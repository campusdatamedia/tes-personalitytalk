<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TesSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tes_settings';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_ts';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_hrd', 'id_paket', 'waktu_pengerjaan', 'waktu_hafalan', 'is_auth', 'access_token'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
