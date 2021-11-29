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
					<li class="breadcrumb-item active" aria-current="page">Detail Paket Soal</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-auto mb-3">
			@include('template/admin/_sidebar')
		</div>
		<div class="col-md">
			<!-- Content -->
			<div class="card">
				<h5 class="card-header">Detail Paket Soal</h5>
				<div class="card-body">
					<h5 class="card-title">Informasi</h5>
					<div class="table-responsive">
						<table class="table table-sm">
							<tr>
								<td>Nama Paket</td>
								<td>{{ $paket->nama_paket }}</td>
							</tr>
							@if($paket->part != 0)
							<tr>
								<td>Bagian</td>
								<td>{{ $paket->part }}</td>
							</tr>
							@endif
							<tr>
								<td>Tes</td>
								<td>{{ $paket->nama_tes }}</td>
							</tr>
							<tr>
								<td>Jumlah Soal</td>
								<td>{{ number_format($paket->jumlah_soal,0,',',',') }}</td>
							</tr>
							@if($paket->tipe_soal != '')
							<tr>
								<td>Tipe Soal</td>
								<td>{{ ucfirst($paket->tipe_soal) }}</td>
							</tr>
							@endif
							<tr>
								<td>Waktu Pengerjaan</td>
								<td>{{ $paket->waktu_pengerjaan == 0 ? 'Tidak Ada' : round($paket->waktu_pengerjaan / 60, 2).' menit' }}</td>
							</tr>
							<tr>
								<td>Status</td>
								<td><span class="badge {{ $paket->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $paket->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span></td>
							</tr>
						</table>
					</div>
					@if($paket->deskripsi_paket != '')
					<h5 class="card-title">Deskripsi</h5>
					<p class="mb-0">{!! $paket->deskripsi_paket !!}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css-extra')
<style type="text/css">
	.table tr td:last-child {text-align: right;}
</style>
@endsection