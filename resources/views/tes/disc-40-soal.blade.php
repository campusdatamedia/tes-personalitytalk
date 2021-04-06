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
    @if(Auth::user()->role == 6)
		@if($tes->waktu_tes != null && strtotime('now') < strtotime($tes->waktu_tes))
		<div class="row">
			<!-- Alert -->
			<div class="col-12 mb-2">
				<div class="alert alert-danger fade show text-center" role="alert">
					Tes akan dilaksanakan pada tanggal <strong>{{ setFullDate($tes->waktu_tes) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($tes->waktu_tes)) }}</strong>.
				</div>
			</div>
		</div>
		@elseif($check != null)
		<div class="row">
			<!-- Alert -->
			<div class="col-12 mb-2">
				<div class="alert alert-danger fade show text-center" role="alert">
					Anda sudah melakukan tes.
				</div>
			</div>
		</div>
		@endif
    @endif
	<div class="row" style="margin-bottom:100px">
	    <div class="col-12">
    		<div class="card shadow bg-light">
                <div class="card-header bg-warning py-3">
                    <h5 class="m-0 font-weight-bold text-dark text-center">Tes DISC 40 Soal</h5>
                </div>
    			<div class="card-body">
    				<form id="form" method="post" action="/tes/{{ $path }}/store">
    				    {{ csrf_field() }}
    				    <input type="hidden" name="path" value="{{ $path }}">
    				    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
    				    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
    					<div class="container-fluid">
    						<div class="row">
    						    @foreach($soal as $data)
    							<div class="col-lg-6 mb-3">
    								<table class="table table-borderless bg-white border border-warning">
    									<thead>
    										<tr>
    											<th width="50"></th>
    											<th width="50"><i class="fa fa-thumbs-up text-success"></i></th>
    											<th width="50"><i class="fa fa-thumbs-down text-danger"></i></th>
    											<th>Karakteristik</th>
    										</tr>
    									</thead>
    									<tbody>
    										<tr>
    											<td rowspan="4" class="font-weight-bold num" data-id="{{ $data->nomor }}">{{ $data->nomor }}</td>
    											<td><input type="radio" name="m[{{ $data->nomor }}]" class="{{ $data->nomor }}m" value="{{ $data->soal[0]['disc']['A'] }}"></td>
    											<td><input type="radio" name="l[{{ $data->nomor }}]" class="{{ $data->nomor }}l" value="{{ $data->soal[0]['disc']['A'] }}"></td>
    											<td>{{ $data->soal[0]['pilihan']['A'] }}</td>
    										</tr>
    										<tr>
    											<td><input type="radio" name="m[{{ $data->nomor }}]" class="{{ $data->nomor }}m" value="{{ $data->soal[0]['disc']['B'] }}"></td>
    											<td><input type="radio" name="l[{{ $data->nomor }}]" class="{{ $data->nomor }}l" value="{{ $data->soal[0]['disc']['B'] }}"></td>
    											<td>{{ $data->soal[0]['pilihan']['B'] }}</td>
    										</tr>
    										<tr>
    											<td><input type="radio" name="m[{{ $data->nomor }}]" class="{{ $data->nomor }}m" value="{{ $data->soal[0]['disc']['C'] }}"></td>
    											<td><input type="radio" name="l[{{ $data->nomor }}]" class="{{ $data->nomor }}l" value="{{ $data->soal[0]['disc']['C'] }}"></td>
    											<td>{{ $data->soal[0]['pilihan']['C'] }}</td>
    										</tr>
    										<tr>
    											<td><input type="radio" name="m[{{ $data->nomor }}]" class="{{ $data->nomor }}m" value="{{ $data->soal[0]['disc']['D'] }}"></td>
    											<td><input type="radio" name="l[{{ $data->nomor }}]" class="{{ $data->nomor }}l" value="{{ $data->soal[0]['disc']['D'] }}"></td>
    											<td>{{ $data->soal[0]['pilihan']['D'] }}</td>
    										</tr>
    									</tbody>
    								</table>
    							</div>
    							@endforeach
    						</div>
    					</div>
    				</form>
    			</div>
    		</div>
    	</div>
	</div>
	<nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-light border-top">
		<ul class="navbar nav ml-auto">
			<li class="nav-item">
				<span id="answered">0</span>/<span id="total"></span> Soal Terjawab
			</li>
			<li class="nav-item ml-3">
				<a href="#" class="text-secondary" data-toggle="modal" data-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
			</li>
			<li class="nav-item ml-3">
				<button class="btn btn-md btn-primary text-uppercase rounded-0" id="btn-submit" disabled>Submit</button>
			</li>
		</ul>
	</nav>
	<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">Tutorial Tes</h5>
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          			<span aria-hidden="true">&times;</span>
	        		</button>
	      		</div>
		      	<div class="modal-body">
		        	<p>Tes ini terdiri dari 40 Soal dan 2 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit.</p>
		        	<ul>
		        		<li>Pelajari semua jawaban pada setiap pilihan</li>
		        		<li>Pilih satu jawaban yang <strong>paling mendekati diri kamu</strong> (<i class="fa fa-thumbs-up text-success"></i>)</li>
		        		<li>Pilih satu jawaban yang <strong>paling tidak mendekati diri kamu</strong> (<i class="fa fa-thumbs-down text-danger"></i>)</li>
		        	</ul>
		        	<p>Pada setiap soal harus memiliki jawaban <u>satu</u> <strong>paling mendekati diri kamu</strong> dan hanya <u>satu</u> <strong>paling tidak mendekati diri kamu</strong>.</p>
		        	<p>Terkadang akan sedikit sulit untuk memutuskan jawaban yang terbaik. Ingat, tidak ada jawaban yang benar atau salah dalam tes ini.</p>
		        	<p>Maka pikirkan baik-baik.</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase rounded-0" data-dismiss="modal">Mengerti</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	// vertical align modal
	$(document).ready(function(){
		// Show modal when the page is loaded
		$("#tutorialModal").modal("toggle");

	    function alignModal(){
	        var modalDialog = $(this).find(".modal-dialog");
	        
	        // Applying the top margin on modal dialog to align it vertically center
	        modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
	    }
	    // Align modal when it is displayed
	    $(".modal").on("shown.bs.modal", alignModal);
	    
	    // Align modal when user resize the window
	    $(window).on("resize", function(){
	        $(".modal:visible").each(alignModal);
	    });

	    totalQuestion();
	});

	// Change value
	$(document).on("change", "input[type=radio]", function(){
		var className = $(this).attr("class");
		var currentNumber = className.replace(/\D/g,'');
		var currentCode = className.charAt(className.length-1);
		var oppositeCode = currentCode == "m" ? "l" : "m";
		var currentValue = $(this).val();
		var oppositeValue = $("." + currentNumber + oppositeCode + ":checked").val();

		// Detect if one question has same answer
		if(currentValue == oppositeValue){
			$("." + currentNumber + oppositeCode + ":checked").prop("checked", false);
			oppositeValue = $("." + currentNumber + oppositeCode + ":checked").val();
		}

		// Count answered question
		countAnswered();

		// Enable submit button
		countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Count answered question
	function countAnswered(){
		var total = 0;
		$(".num").each(function(key, elem){
			var id = $(elem).data("id");
			var mValue = $("." + id + "m:checked").val();
			var lValue = $("." + id + "l:checked").val();
			mValue != undefined && lValue != undefined ? total++ : "";
		});
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = $("input[type=radio]").length;
		var pointPerQuestion = 4;
		var total = totalRadio / pointPerQuestion / 2;
		$("#total").text(total);
		return total;
	}

	// Submit form
	$(document).on("click", "#btn-submit", function(e){
		e.preventDefault();
		$("#form")[0].submit();
	});
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
</style>
@endsection