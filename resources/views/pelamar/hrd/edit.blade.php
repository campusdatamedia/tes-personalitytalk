@extends('template/hrd/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Edit Pelamar</h1>
  <p class="mb-4">Pelamar yang melamar pekerjaan dari lowongan perusahaan.</p>

  <!-- DataTables Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Edit Pelamar</h6>
    </div>
    <div class="card-body">
      <form method="post" action="/hrd/pelamar/update">
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
                  Untuk Jabatan: {{ $pelamar->posisi->posisi }}
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
                <td>
                  <input type="text" name="nama_lengkap" class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" value="{{ $pelamar->nama_lengkap }}">
                  @if($errors->has('nama_lengkap'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('nama_lengkap')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>
                  <div class="d-md-flex">
                    <input type="text" name="tempat_lahir" class="form-control {{ $errors->has('tempat_lahir') ? 'is-invalid' : '' }} col-md" value="{{ $pelamar->tempat_lahir }}">
                    <input type="text" name="tanggal_lahir" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }} col-md ml-md-2 mt-2 mt-md-0" value="{{ $pelamar->tanggal_lahir }}">
                  </div>
                </td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                  <select name="jenis_kelamin" class="form-control custom-select col-lg-4">
                    <option value="" disabled>--Pilih--</option>
                    <option value="L" {{ $pelamar->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                    <option value="P" {{ $pelamar->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @if($errors->has('jenis_kelamin'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('jenis_kelamin')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Agama</td>
                <td>:</td>
                <td>
                  <select name="agama" class="form-control custom-select col-lg-4">
                    <option value="" disabled>--Pilih--</option>
                    @foreach($agama as $data)
                    <option value="{{ $data->id_agama }}" {{ $pelamar->agama == $data->id_agama ? 'selected' : '' }}>{{ $data->nama_agama }}</option>
                    @endforeach
                    <option value="99" {{ $pelamar->agama == 99 ? 'selected' : '' }}>Lain-Lain</option>
                  </select>
                  @if($errors->has('agama'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('agama')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td>
                  <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $pelamar->email }}">
                  @if($errors->has('email'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('email')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Nomor HP</td>
                <td>:</td>
                <td>
                  <input type="text" name="nomor_hp" class="form-control" value="{{ $pelamar->nomor_hp }}">
                  @if($errors->has('nomor_hp'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('nomor_hp')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                  <textarea name="alamat" class="form-control" rows="3">{{ $pelamar->alamat }}</textarea>
                  @if($errors->has('alamat'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('alamat')) }}
                  </small>
                  @endif
                </td>
              </tr>
              <tr>
                <td>Pendidikan Terakhir</td>
                <td>:</td>
                <td>
                  <textarea name="pendidikan_terakhir" class="form-control" rows="3">{{ $pelamar->pendidikan_terakhir }}</textarea>
                  @if($errors->has('pendidikan_terakhir'))
                  <small class="text-danger">
                    {{ ucfirst($errors->first('pendidikan_terakhir')) }}
                  </small>
                  @endif
                </td>
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
                      <td><input type="text" name="akun_sosmed[{{ $sosmed }}]" class="form-control" value="{{ $akun }}"></td>
                    </tr>
                    @endforeach
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="form-group mt-3">
          <input type="hidden" name="id" value="{{ $pelamar->id_pelamar }}">
          <input type="hidden" name="id_user" value="{{ $pelamar->id_user }}">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="/hrd/pelamar" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('js-extra')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(function(){
    $('input[name=tanggal_lahir]').datepicker({
      format: 'yyyy-mm-dd',
    });

    $(document).on("click", ".btn-show-datepicker", function(e){
      e.preventDefault();
      $('input[name=tanggal_lahir]').focus();
    })
  });
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<style type="text/css">
  .table {min-width: 600px;}
  .table tr td {padding: .5rem;}
  .table tr td:first-child {font-weight: bold; min-width: 200px; width: 200px}
</style>

@endsection