<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pelamar';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_pelamar';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'nama_lengkap',
		'tempat_lahir',
		'tanggal_lahir',
        'jenis_kelamin',
		'agama',
		'email',
        'nomor_hp',
		'alamat',
		'pendidikan_terakhir',
        'umur',
        'nomor_ktp',
        'nomor_telepon',
        'status_hubungan',
        'kode_pos',
        'data_darurat',
        'akun_sosmed',
        'pendidikan_formal',
        'pendidikan_non_formal',
        'pengalaman_kerja',
        'keahlian',
        'pertanyaan',
		'pas_foto',
		'foto_ijazah',
        'id_user',
		'posisi',
		'created_at',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
