<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'karyawan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_karyawan';

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
        'id_hrd',
        'nama_lengkap',
		'tanggal_lahir',
        'jenis_kelamin',
		'email',
		'nomor_hp',
		'nik_cis',
		'nik',
		'alamat',
		'pendidikan_terakhir',
		'awal_bekerja',
		'posisi',
		'kantor',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
