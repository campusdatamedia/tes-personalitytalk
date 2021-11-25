<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lowongan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_lowongan';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'judul_lowongan',
		'deskripsi_lowongan',
		'posisi',
		'url_lowongan',
		'status',
		'created_at',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
