@extends('template/main')

@section('content')

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $packet->name }}</h3>
		<p class="m-0"><a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#tutorialModal"><u>Lihat Petunjuk Pengerjaan Disini</u></a></p>
    </div>
</div>

<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>

<input type="hidden" id="user_id" value="{{ Auth::user()->id }}">

<div class="container main-container">
    @if($selection != null)
	    @if(strtotime('now') < strtotime($selection->test_time))
	    <div class="row">
	        <div class="col-12 mb-2">
	            <div class="alert alert-danger fade show text-center" role="alert">
	                Tes akan dilaksanakan pada tanggal <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
	            </div>
	        </div>
	    </div>
	    @endif
    @endif

	<div id="question" class="row" style="margin-bottom: 100px;">
		<!-- Button Navigation -->
		<div class="col-md-3 mb-3 mb-md-0">
			<div class="card">
				<div class="card-header fw-bold text-center">Navigasi Soal</div>
				<div class="card-body"></div>
			</div>
		</div>

		<!-- Card Question -->
		<div class="col-md-9">
			<div class="card card-question">
				<div class="card-header">
					<span class="fw-bold"><i class="fa fa-edit"></i> Soal</span>
				</div>
				<div class="card-body"></div>
				<div class="card-footer bg-white text-center"></div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-extra')

<!-- React JS -->
<script>let exports = {};</script>
<script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script type="text/babel" src="{{ asset('assets/react/App.js') }}"></script>
<script type="text/babel">
	ReactDOM.render(<App/>, document.getElementById('question'));
</script>

@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px; overflow-y: auto; max-height: calc(100vh - 200px);}
	.table {margin-bottom: 0;}
	.radio-image {margin-bottom: 1rem; padding-left: 0;}
	.radio-image label {cursor: pointer;}
	.radio-image label.border-primary {border-color: var(--color-1)!important; border-width: 2px!important;}
	/* #form {filter: blur(3px);} */

	.modal-auth .card-question, .modal-auth #nav-button {filter: blur(3px);}
	.modal-open .card-question .card-body {filter: blur(3px);}
	#question .btn:focus {box-shadow: none;}
	#nav-button {text-align: center;}
	#nav-button .btn {font-size: .75rem; width: 3.75rem; margin: .25rem;}
</style>
@endsection