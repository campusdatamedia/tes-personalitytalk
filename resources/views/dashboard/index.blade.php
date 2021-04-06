@extends('template/main')

@section('content')
    @if($seleksi != null)
    @if(strtotime('now') < strtotime($seleksi->waktu_wawancara))
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
    @if($seleksi != null)
    @if(strtotime('now') >= strtotime($seleksi->waktu_wawancara))
    <div class="row">
        <div class="col-12 mb-4">
            <!-- Tes -->
            <div class="card shadow mb-4">
                <div class="card-header bg-warning py-3">
                    <h5 class="m-0 font-weight-bold text-dark text-center">Tes Kepribadian</h5>
                </div>
                <div class="card-body">
                    @if(Session::get('message'))
                    <div class="row">
                        <!-- Alert -->
                        <div class="col-12 mb-2">
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row justify-content-center">
                        @if(count($tes)>0)
                            @foreach($tes as $data)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="/tes/{{ $data->path }}" class="btn btn-md btn-block btn-outline-primary font-weight-bold">{{ $data->nama_tes }}</a>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12 mb-0">
                                <div class="alert alert-danger fade show text-center mb-0" role="alert">
                                    Tidak ada tes yang akan dilakukan.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    @if(Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3 || Auth::user()->role == 5)
    <div class="row">
        <div class="col-12 mb-4">
            <!-- Tes -->
            <div class="card shadow mb-4">
                <div class="card-header bg-warning py-3">
                    <h5 class="m-0 font-weight-bold text-dark text-center">Tes Kepribadian</h5>
                </div>
                <div class="card-body">
                    @if(Session::get('message'))
                    <div class="row">
                        <!-- Alert -->
                        <div class="col-12 mb-2">
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {{ Session::get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row justify-content-center">
                        @if(count($tes)>0)
                            @foreach($tes as $data)
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="/tes/{{ $data->path }}" class="btn btn-md btn-block btn-outline-primary font-weight-bold">{{ $data->nama_tes }}</a>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12 mb-0">
                                <div class="alert alert-danger fade show text-center mb-0" role="alert">
                                    Tidak ada tes yang akan dilakukan.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(Auth::user()->role == 6)
		@if($check != null)
		<div class="row">
			<!-- Alert -->
			<div class="col-12 mb-2">
				<div class="alert alert-danger fade show text-center" role="alert">
					Anda sudah melakukan tes.
				</div>
			</div>
		</div>
		@else
		<div class="row">
			<div class="col-12 mb-4">
				<!-- Tes -->
				<div class="card shadow mb-4">
					<div class="card-header bg-warning py-3">
						<h5 class="m-0 font-weight-bold text-dark text-center">Tes Kepribadian</h5>
					</div>
					<div class="card-body">
						@if(Session::get('message'))
						<div class="row">
							<!-- Alert -->
							<div class="col-12 mb-2">
								<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
									{{ Session::get('message') }}
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							</div>
						</div>
						@endif
						<div class="row justify-content-center">
							<div class="col-md-3 col-sm-6 mb-3">
								<a href="/tes/disc-40-soal" class="btn btn-md btn-block btn-outline-primary font-weight-bold">Tes DISC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
    @endif
@endsection