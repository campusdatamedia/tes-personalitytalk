@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
	<h3 class="m-0 text-center text-white d-none">Tes</h3>
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
					<li class="breadcrumb-item"><a href="/admin/tes">Tes</a></li>
					<li class="breadcrumb-item active" aria-current="page">Data Tes</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-auto mb-3">
			@include('template/admin/_sidebar')
		</div>
		<div class="col-md">
			<!-- Content -->
			<div class="card">
				<h5 class="card-header">Data Tes</h5>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-sm table-hover table-striped table-bordered" id="datatable">
							<thead>
								<tr>
									<th width="40">No</th>
									<th>Tes</th>
									<th>Path</th>
								</tr>
							</thead>
							<tbody>
								@foreach($tes as $key=>$data)
								<tr>
									<td class="number">{{ ($key+1) }}</td>
									<td>{{ $data->nama_tes }}</td>
									<td>{{ $data->path }}</td>
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
		});
	});
</script>
@endsection

@section('css-extra')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection