<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hasil';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_hasil';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_user',
        'id_tes',
        'id_paket',
		'hasil',
        'test_at',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
