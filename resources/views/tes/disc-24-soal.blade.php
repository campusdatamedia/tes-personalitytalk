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
    @if($seleksi == null || ($seleksi != null && strtotime('now') >= strtotime($seleksi->waktu_wawancara)))
	<div class="row" style="margin-bottom:100px">
	    <div class="col-12">
    		<div class="card shadow bg-light">
                <div class="card-header bg-warning py-3">
                    <h5 class="m-0 font-weight-bold text-dark text-center">Tes DISC 24 Soal</h5>
                </div>
    			<div class="card-body">
    			    <form id="form" method="post" action="/tes/{{ $path }}/store">
    				    <input type="hidden" name="path" value="{{ $path }}">
    				    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
    				    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
    			        <input type="hidden" id="D" name="Dm">
                    	<input type="hidden" id="I" name="Im">
                    	<input type="hidden" id="S" name="Sm">
                    	<input type="hidden" id="C" name="Cm">
                    	<input type="hidden" id="B" name="Bm">
                    	<input type="hidden" id="K" name="Dl">
                    	<input type="hidden" id="O" name="Il">
                    	<input type="hidden" id="L" name="Sl">
                    	<input type="hidden" id="E" name="Cl">
                    	<input type="hidden" id="H" name="Bl">
                		@csrf
                		<div class="row">
                			@php
                				$totalsoal = 0;
                			@endphp
                			@foreach($soal as $data)
                			<div class="col-md-6" style="margin-top: 20px;">
                				<div class="card">
                					<div class="card-body">
                						<table width="100%">
                							<tr>
                								<td></td>
                								<td><i style="color:#56DB28" class="fa fa-thumbs-up"></i></td>
                								<td><i style="color:#E3451E" class="fa fa-thumbs-down"></i></td>
                								<td><h6 class="card-title" style="font-weight: bold; color: #9A9797;">Gambaran Diri</h6></td>
                							</tr>
                							@php
                							$huruf = ['A', 'B', 'C' , 'D'];
                							$num = -1;
                							$totalsoal = $totalsoal+1;
                							$json = json_decode($data->soal);
                							@endphp
                							@foreach($json as $pilihan)
                							@php
                							$num++;
                							$key = $huruf[$num];
                							@endphp
                							<tr>
                								@if($key == 'A')
                								<td width="30" rowspan="4" valign="top" data-id="{{ $data->nomor }}" class="num"><h6 class="card-title"  style="font-weight: bold; color: #9A9797;">{{$data->nomor}}</h6></td>
                								@endif
                								<td width="30" valign="top">
                									<label class="cont">
                										<input type="radio" name="y[{{$data->nomor}}]" id="{{$pilihan->keym}}m" class="{{$data->nomor}}-y" value="{{$key}}">
                										<span class="checkmark"></span>
                									</label>
                								</td>
                								<td width="30" valign="top">
                									<label class="cont">
                										<input type="radio" name="n[{{$data->nomor}}]" id="{{$pilihan->keyl}}l" class="{{$data->nomor}}-n" value="{{$key}}">
                										<span class="checkmark"></span>
                									</label>
                								</td>
                								<td><p style="color: #9A9797; font-size: 15px;">{{$pilihan->pilihan}}</p></td>
                							</tr>
                							@endforeach
                						</table>
                					</div>
                				</div>
                			</div>
                			@endforeach
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
    				<p>"Tes ini terdiri dari 24 Soal dan 2 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit"</p>
    				<ul>
    					<li>Pelajari semua jawaban pada setiap pilihan</li>
    					<li>
    						Pilih satu jawaban yang
    						<strong>paling mendekati diri kamu</strong>
    						(
    							<i style="color:#56DB28" class="fa fa-thumbs-up"></i>
    						)
    					</li>
    					<li>
    						Pilih satu jawaban yang
    						<strong>paling tidak mendekati diri kamu</strong>
    						( 
    							<i style="color:#E3451E" class="fa fa-thumbs-down"></i>
    						)
    					</li>
    				</ul><br>
    				<p>
    					Pada setiap soal harus memiliki jawaban
    					<ins>satu</ins>
    					<strong>paling mendekati diri kamu</strong>
    					dan hanya
    					<ins>satu</ins>
    					<strong>paling tidak mendekati diri kamu</strong>.
    				</p>
    				<p>
    					Terkadang akan sedikit sulit untuk memutuskan jawaban yang terbaik. Ingat, tidak ada jawaban yang benar atau salah dalam tes ini.
    				</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase rounded-0" data-dismiss="modal">Mengerti</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
    @endif
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
		var currentNumber = className.split("-")[0];
		var currentCode = className.split("-")[1];
		var oppositeCode = currentCode == "y" ? "n" : "y";
		var currentValue = $(this).val();
		var oppositeValue = $("." + currentNumber + "-" + oppositeCode + ":checked").val();

		// Detect if one question has same answer
		if(currentValue == oppositeValue){
			$("." + currentNumber + "-" + oppositeCode + ":checked").prop("checked", false);
			oppositeValue = $("." + currentNumber + "-" + oppositeCode + ":checked").val();
		}

		var Dm = $('#Dm:checked').length
		var Im = $('#Im:checked').length
		var Sm = $('#Sm:checked').length
		var Cm = $('#Cm:checked').length
		var Bm = $('#Bm:checked').length
		document.getElementById('D').value = Dm;
		document.getElementById('I').value = Im;
		document.getElementById('S').value = Sm;
		document.getElementById('C').value = Cm;
		document.getElementById('B').value = Bm;

		var Dl = $('#Dl:checked').length
		var Il = $('#Il:checked').length
		var Sl = $('#Sl:checked').length
		var Cl = $('#Cl:checked').length
		var Bl = $('#Bl:checked').length
		document.getElementById('K').value = Dl;
		document.getElementById('O').value = Il;
		document.getElementById('L').value = Sl;
		document.getElementById('E').value = Cl;
		document.getElementById('H').value = Bl;

		// Count answered question
		countAnswered();

		// Enable submit button
		var totalQuestion = document.getElementById('total').innerHTML;
		countAnswered() >= totalQuestion ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Count answered question
	function countAnswered(){
		var total = 0;
		$(".num").each(function(key, elem){
			var id = $(elem).data("id");
			var mValue = $("." + id + "-y:checked").val();
			var lValue = $("." + id + "-n:checked").val();
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