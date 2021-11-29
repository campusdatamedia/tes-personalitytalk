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
					<li class="breadcrumb-item active" aria-current="page">Data Soal</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-auto mb-3">
			@include('template/admin/_sidebar')
		</div>
		<div class="col-md">
			<!-- Content -->
			<div class="card">
				<h5 class="card-header">Data Soal</h5>
				<div class="card-body">
					@if(count($soal)>0)
						<div class="accordion" id="accordionExample">
							@foreach($soal as $key=>$data)
							<div class="card">
								<div class="card-header px-3 py-2" id="heading-{{ $key }}">
									<a href="#" data-toggle="collapse" data-target="#collapse-{{ $key }}" aria-expanded="true" aria-controls="collapse-{{ $key }}">Soal #{{ $data->nomor }}</a>
								</div>
								<div id="collapse-{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $key }}" data-parent="#accordionExample">
									<div class="card-body p-3">
										@if($paket->path == 'disc-40-soal')
											@php $detail_soal = json_decode($data->soal, true); $detail_soal = $detail_soal[0]; @endphp
											<table class="table table-sm table-bordered mb-0">
												<thead>
													<tr>
														<th>Pilihan</th>
														<th>DISC</th>
													</tr>
												</thead>
												<tbody>
													@foreach($detail_soal['pilihan'] as $key2=>$data2)
													<tr>
														<td>{{ $detail_soal['pilihan'][$key2] }}</td>
														<td>{{ $detail_soal['disc'][$key2] }}</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										@elseif($paket->path == 'disc-24-soal')
											@php $detail_soal = json_decode($data->soal, true); @endphp
											<table class="table table-sm table-bordered mb-0">
												<thead>
													<tr>
														<th>Pilihan</th>
														<th>Most</th>
														<th>Least</th>
													</tr>
												</thead>
												<tbody>
													@foreach($detail_soal as $data2)
													<tr>
														<td>{{ $data2['pilihan'] }}</td>
														<td>{{ $data2['keym'] }}</td>
														<td>{{ $data2['keyl'] }}</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										@elseif($paket->path == 'ist')
											@php $detail_soal = json_decode($data->soal, true); $detail_soal = $detail_soal[0]; @endphp
											@if($paket->tipe_soal == 'choice' || $paket->tipe_soal == 'choice-memorizing')
												<p><strong>Soal:</strong><br> {{ $detail_soal['soal'] }}</p>
												<table class="table table-sm table-bordered mb-0">
													<thead>
														<tr>
															<th>Pilihan</th>
															<th>Jawaban</th>
														</tr>
													</thead>
													<tbody>
														@foreach($detail_soal['pilihan'] as $key=>$pilihan)
														<tr>
															<td>{{ $pilihan }}</td>
															<td><span class="{{ $key == $detail_soal['jawaban'] ? 'text-success' : 'text-danger' }}">{{ $key == $detail_soal['jawaban'] ? 'Benar' : 'Salah' }}</span></td>
														</tr>
														@endforeach
													</tbody>
												</table>
											@elseif($paket->tipe_soal == 'essay')
												<p><strong>Soal:</strong><br> {{ $detail_soal['soal'] }}</p>
												<table class="table table-sm table-bordered mb-0">
													<thead>
														<tr>
															<th width="50%">Jawaban (2 poin)</th>
															<th width="50%">Jawaban (1 poin)</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>{{ $detail_soal['jawaban'][2] }}</td>
															<td>{{ $detail_soal['jawaban'][1] }}</td>
														</tr>
													</tbody>
												</table>
											@elseif($paket->tipe_soal == 'number')
												<p><strong>Soal:</strong><br> {{ $detail_soal['soal'] }}</p>
												@php
													$jawaban = str_split($detail_soal['jawaban']);
													$jawaban = implode(', ', $jawaban);
												@endphp
												<p><strong>Jawaban:</strong><br> {{ $jawaban }} </p>
											@elseif($paket->tipe_soal == 'image')
												<p><strong>Soal:</strong><br> <img width="125" src="{{ asset('assets/images/tes/ist/'.$detail_soal['soal']) }}"></p>
												<div class="row"> 
													@foreach($detail_soal['pilihan'] as $key=>$pilihan)
		                                                <div class="col-auto">
	                                                    	<img width="100" src="{{ asset('assets/images/tes/ist/'.$pilihan) }}">
	                                                    	<p class="text-center mb-0 {{ $key == $detail_soal['jawaban'] ? 'text-success' : 'text-danger' }}">{{ $key == $detail_soal['jawaban'] ? 'Benar' : 'Salah' }}</p>
		                                                </div>
													@endforeach
												</div>
											@endif
										@endif
									</div>
								</div>
							</div>
							@endforeach
						</div>
					@else
						<div class="alert alert-danger mb-0 text-center">
							Tidak ada soal.
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection