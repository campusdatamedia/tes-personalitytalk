@extends('template/applicant/template')

@section('content')

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tes {{ $tes->nama_tes }}</h1>
    <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
  </div>

  @if($seleksi != null)
    @if(strtotime('now') <= strtotime($seleksi->waktu_wawancara))
      <!-- Content Row -->
      <div class="row">
        <!-- Alert -->
        <div class="col-12 mb-2">
          <div class="alert alert-danger fade show text-center" role="alert">
            Tes akan dilaksanakan pada tanggal <strong>{{ setFullDate($seleksi->waktu_wawancara) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($seleksi->waktu_wawancara)) }}</strong>.
          </div>
        </div>
      </div>
    @endif
  @endif

@endsection

@section('css-extra')

<style type="text/css">
  .acara .fa {width: 20px; text-align: center;}
</style>

@endsection

@section('js-extra')

<!-- Page level plugins -->
<!-- <script src="{{ asset('templates/sb-admin-2/vendor/chart.js/Chart.min.js') }}"></script> -->

<!-- Page level custom scripts -->
<!-- <script src="{{ asset('templates/sb-admin-2/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('templates/sb-admin-2/js/demo/chart-pie-demo.js') }}"></script> -->

@endsection