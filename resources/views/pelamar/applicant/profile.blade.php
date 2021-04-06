@extends('template/applicant/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Profil</h1>
  <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

  <!-- DataTables Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Profil</h6>
    </div>
    <div class="card-body">
      <form method="post" action="#">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="col-auto p-3 border border-muted mb-2 mr-2">
            <img src="{{ asset('assets/images/pas-foto/'.$pelamar->pas_foto) }}" class="img-fluid" width="200">
          </div>
          <div class="col">
            <div class="row">
              <div class="col-sm-auto ml-sm-auto">
                <p class="font-weight-bold text-md-right">
                  <small>Melamar tanggal {{ setFullDate($pelamar->created_at) }}, pukul {{ date('H:i:s', strtotime($pelamar->created_at)) }}</small>
                  <br>
                  Untuk Jabatan: {{ $pelamar->posisi }}
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>Nama Lengkap</td>
                <td width="10">:</td>
                <td>{{ $pelamar->nama_lengkap }}</td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $pelamar->tempat_lahir }}, {{ date('d F Y', strtotime($pelamar->tanggal_lahir)) }}</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $pelamar->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
              </tr>
              <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $pelamar->agama }}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $pelamar->email }}</td>
              </tr>
              <tr>
                <td>Nomor HP</td>
                <td>:</td>
                <td>{{ $pelamar->nomor_hp }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $pelamar->alamat }}</td>
              </tr>
              <tr>
                <td>Pendidikan Terakhir</td>
                <td>:</td>
                <td>{{ $pelamar->pendidikan_terakhir }}</td>
              </tr>
              <tr>
                <td>Akun Sosial Media</td>
                <td>:</td>
                <td>
                  <table class="table table-bordered mb-0">
                    @foreach($pelamar->akun_sosmed as $sosmed=>$akun)
                    <tr>
                      <td width="150">{{ $sosmed }}</td>
                      <td width="10">:</td>
                      <td>{{ $akun }}</td>
                    </tr>
                    @endforeach
                  </table>
                </td>
              </tr>
              <tr>
                <td>Pas Foto</td>
                <td>:</td>
                <td><a class="btn btn-sm btn-primary" href="{{ asset('assets/images/pas-foto/'.$pelamar->pas_foto) }}" target="_blank"><i class="fa fa-camera mr-2"></i> Lihat Foto</a></td>
              </tr>
              <tr>
                <td>Foto Ijazah</td>
                <td>:</td>
                <td><a class="btn btn-sm btn-primary" href="{{ asset('assets/images/foto-ijazah/'.$pelamar->foto_ijazah) }}" target="_blank"><i class="fa fa-camera mr-2"></i> Lihat Foto</a></td>
              </tr>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('css-extra')

<style type="text/css">
  .table {min-width: 600px;}
  .table tr td {padding: .5rem;}
  .table tr td:first-child {font-weight: bold; min-width: 200px; width: 200px;}
</style>

@endsection