<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRD extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hrd';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_hrd';

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
        'nama_lengkap',
		'tanggal_lahir',
        'jenis_kelamin',
		'email',
		'kode',
		'perusahaan',
		'akses_tes',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
