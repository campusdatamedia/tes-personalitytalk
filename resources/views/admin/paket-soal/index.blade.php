@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
	<h3 class="m-0 text-center text-white d-none">Paket Soal</h3>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
	<div class="row">
		<div class="col-12">
			<!-- Breadcrumb -->
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/admin">Admin Area</a></li>
					<li class="breadcrumb-item"><a href="/admin/paket-soal">Paket Soal</a></li>
					<li class="breadcrumb-item active" aria-current="page">Data Paket Soal</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-auto mb-3">
			@include('template/admin/_sidebar')
		</div>
		<div class="col-md">
			<!-- Content -->
			<div class="card">
				<h5 class="card-header">Data Paket Soal</h5>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-sm table-hover table-striped table-bordered" id="datatable">
							<thead>
								<tr>
									<th width="40">No</th>
									<th>Paket Soal</th>
									<th>Tes</th>
									<th width="60">Jumlah</th>
									<th width="60">Status</th>
									<th width="80">Opsi</th>
								</tr>
							</thead>
							<tbody>
								@foreach($paket as $key=>$data)
								<tr>
									<td class="number">{{ ($key+1) }}</td>
									<td>
										<a href="/admin/paket-soal/detail/{{ $data->id_paket }}">{{ $data->nama_paket }}</a>
										@if($data->part != 0)
											<br>
											<span class="small">Bagian: {{ $data->part }}</span>
										@endif
									</td>
									<td>{{ $data->nama_tes }}</td>
									<td>{{ number_format($data->jumlah_soal,0,',',',') }}</td>
									<td><span class="badge {{ $data->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span></td>
									<td class="options">
										<div class="btn-group">
											<a href="/admin/paket-soal/detail/{{ $data->id_paket }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Lihat Detail"><i class="fa fa-eye"></i></a>
											<a href="/admin/paket-soal/soal/{{ $data->id_paket }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Lihat Soal"><i class="fa fa-list"></i></a>
											<a href="#" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-extra')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#datatable").DataTable({
			"language": datatableLang,
			columnDefs: [
                {orderable: false, targets: -1},
            ]
        });
	});
</script>
@endsection

@section('css-extra')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection