@extends('template/hrd/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Profil Pelamar</h1>
  <p class="mb-4">Pelamar yang melamar pekerjaan dari lowongan perusahaan.</p>

  <!-- DataTables Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Profil Pelamar</h6>
      <a class="btn btn-sm btn-primary" href="/hrd/pelamar/edit/{{ $pelamar->id_pelamar }}">
        <i class="fas fa-edit fa-sm fa-fw text-gray-400"></i> Edit Pelamar
      </a>
    </div>
    <div class="card-body">
      <form method="post" action="#">
        {{ csrf_field() }}
        @if(Session::get('message') != null)
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
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
                <td>{{ $pelamar->nama_lengkap }}</td>
              </tr>
              <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $pelamar->tempat_lahir }}, {{ setFullDate($pelamar->tanggal_lahir) }}</td>
              </tr>
              <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $pelamar->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
              </tr>
              <tr>
                <td>Agama</td>
                <td>:</td>
                <td>{{ $pelamar->nama_agama }}</td>
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
        @if(!$seleksi)
        <div class="form-group mt-3">
          <button type="button" class="btn btn-success btn-submit" id="lolos">Atur Wawancara</button>
          <!-- <button type="button" class="btn btn-danger btn-submit" id="tidak-lolos">Tidak Lolos</button> -->
          <a href="/hrd/pelamar" class="btn btn-secondary">Kembali</a>
        </div>
        @endif
      </form>
    </div>
  </div>

  @if(!$seleksi)
  <!-- Set Test Time Modal-->
  <div class="modal fade" id="TimeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Waktu Tes Wawancara</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="post" action="/hrd/seleksi/store">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group">
              <label>Tanggal:</label>
              <div class="input-group">
                <input type="text" id="tanggal" name="tanggal" class="form-control {{ $errors->has('tanggal') ? 'border-danger' : '' }}" value="{{ old('tanggal') }}" placeholder="Format: yyyy-mm-dd" readonly>
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('tanggal') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-datepicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Tanggal"><i class="fa fa-calendar"></i></button>
                </div>
              </div>
              @if($errors->has('tanggal'))
                <small class="text-danger">{{ ucfirst($errors->first('tanggal')) }}</small>
              @endif
            </div>
            <div class="form-group">
              <label>Jam:</label>
              <div class="input-group">
                <input type="text" id="jam" name="jam" class="form-control {{ $errors->has('jam') ? 'border-danger' : '' }}" value="{{ old('jam') }}" placeholder="Format: H:m" readonly data-placement="bottom" data-align="top" data-autoclose="true">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('jam') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-clockpicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Jam"><i class="fa fa-clock"></i></button>
                </div>
              </div>
              @if($errors->has('jam'))
                <small class="text-danger">{{ ucfirst($errors->first('jam')) }}</small>
              @endif
            </div>
            <div class="form-group">
              <label>Tempat:</label>
              <div class="input-group">
                <input type="text" id="tempat" name="tempat" class="form-control {{ $errors->has('tempat') ? 'border-danger' : '' }}" value="{{ old('tempat') }}" placeholder="Tempat Wawancara">
              </div>
              @if($errors->has('tempat'))
                <small class="text-danger">{{ ucfirst($errors->first('tempat')) }}</small>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id_pelamar" value="{{ $pelamar->id_pelamar }}">
            <input type="hidden" name="id_lowongan" value="{{ $pelamar->posisi->id_lowongan }}">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

@endsection

@section('css-extra')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" integrity="sha256-lBtf6tZ+SwE/sNMR7JFtCyD44snM3H2FrkB/W400cJA=" crossorigin="anonymous" />
<style type="text/css">
  .table {min-width: 600px;}
  .table tr td {padding: .5rem;}
  .table tr td:first-child {font-weight: bold; min-width: 200px; width: 200px;}
</style>

@endsection

@section('js-extra')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js" integrity="sha256-LPgEyZbedErJpp8m+3uasZXzUlSl9yEY4MMCEN9ialU=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(function(){
    $('input[name=tanggal]').datepicker({
      format: 'yyyy-mm-dd',
    });

    $("input[name=jam]").clockpicker();

    $(document).on("click", ".btn-show-datepicker", function(e){
      e.preventDefault();
      $('input[name=tanggal]').focus();
    });

    $(document).on("click", ".btn-show-clockpicker", function(e){
      e.preventDefault();
      $('input[name=jam]').focus();
    })
  });

  // Loloskan pelamar
  $(document).on("click", "#lolos", function(e){
    e.preventDefault();
    var ask = confirm("Anda yakin ingin mengatur jadwal wawancara untuk pelamar ini?");
    if(ask){
      $("#TimeTestModal").modal("show");
    }
  });
</script>

@if(count($errors) > 0)
<script type="text/javascript">
  $(function(){
    // Show modal when the page is loaded
    $("#TimeTestModal").modal("toggle");
  });
</script>
@endif

@endsection