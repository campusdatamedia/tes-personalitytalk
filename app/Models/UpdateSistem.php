<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateSistem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'update_sistem';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_update';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'judul_update',
        'deskripsi_update',
		'update_at'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
