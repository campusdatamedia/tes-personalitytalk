@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
	<h3 class="m-0 text-center text-white d-none">Ringkasan</h3>
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
					<li class="breadcrumb-item active" aria-current="page">Ringkasan</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-auto mb-3">
			@include('template/admin/_sidebar')
		</div>
		<div class="col-md">
			<!-- Content -->
			<div class="card">
				<h5 class="card-header">Ringkasan</h5>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-4 col-sm-6">
							<a href="/admin/tes" class="card text-white mb-3" style="background-color: var(--red);">
								<div class="card-body">
									<div class="text-center">
										<h2 class="mb-0">{{ number_format($tes,0,',',',') }}</h2>
										<p class="mb-0">Tes</p>
									</div>
								</div>
							</a>
						</div>
						<div class="col-lg-4 col-sm-6">
							<a href="/admin/paket-soal" class="card text-white mb-3" style="background-color: var(--yellow);">
								<div class="card-body">
									<div class="text-center">
										<h2 class="mb-0">{{ number_format($paket,0,',',',') }}</h2>
										<p class="mb-0">Paket Soal</p>
									</div>
								</div>
							</a>
						</div>
						<div class="col-lg-4 col-sm-6">
							<a href="/admin/hasil" class="card text-white mb-3" style="background-color: var(--blue);">
								<div class="card-body">
									<div class="text-center">
										<h2 class="mb-0">{{ number_format($hasil,0,',',',') }}</h2>
										<p class="mb-0">Hasil</p>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css-extra')
<style type="text/css">
	a.card {transition: top ease-in .25s;}
	a.card:hover {text-decoration: none; top: -.25rem;}
</style>
@endsection