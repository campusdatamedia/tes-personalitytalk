<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kantor';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_kantor';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_hrd',
        'nama_kantor',
		'alamat_kantor',
        'telepon_kantor',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
