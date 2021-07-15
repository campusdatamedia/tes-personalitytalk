@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $paket->nama_paket }}</h3>
        @if($paket->deskripsi_paket != '')
        <hr class="rounded-2" style="border-top: 5px solid rgba(255,255,255,.3)">
        <p class="m-0"><b>SOAL {{ $range_soal }}</b> : <a href="#" class="text-white" data-toggle="modal" data-target="#tutorialModal"><u>Lihat Petunjuk Pengerjaan Disini</u></a></p>
        @endif
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
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
			<form id="form" method="post" action="/tes/{{ $path }}/store">
			    {{ csrf_field() }}
			    <input type="hidden" name="path" value="{{ $path }}">
			    <input type="hidden" name="part" value="{{ $part }}">
			    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
			    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
			    <input type="hidden" name="is_submitted" value="1">
				<div class="">
					<div class="row">
						@if(count($soal)>0)
							@foreach($soal as $num=>$data)
							@php $detail = $data->soal[0]; @endphp
						    <div class="{{ in_array($paket->tipe_soal, ['choice', 'essay']) ? 'col-md-6' : 'col-md-12' }}">
	                            <div class="card soal rounded-1 mb-3">
	                      			<div class="card-header bg-transparent">
						    			<span class="num font-weight-bold" data-id="{{ $data->nomor }}"><i class="fa fa-edit"></i> Soal {{ $data->nomor }}</span>
						    		</div>
	                                <div class="card-body">
	                                    <table class="table table-sm table-borderless">
	                                        <tr>
	                                            <td>
	                                				@if($paket->tipe_soal == 'choice')
	                                					@if($detail['soal'] != '') <p>{{ $detail['soal'] }}</p> @endif
		                                            	@foreach($detail['pilihan'] as $opt=>$pilihan)
		                                                <div class="custom-control custom-radio">
		                                                    <input type="radio" class="custom-control-input radio-{{ $data->nomor }}"
		                                                        id="choice-{{ $data->nomor }}-{{ $opt }}" name="c[{{ $data->nomor }}]"
		                                                        value="{{ $opt }}">
		                                                    <label class="custom-control-label text-justify" for="choice-{{ $data->nomor }}-{{ $opt }}">
		                                                        <span>
		                                                            {{ $pilihan }}
		                                                        </span>
		                                                    </label>
		                                                </div>
		                                                @endforeach
	                                				@elseif($paket->tipe_soal == 'essay')
	                                            		<p>{{ $detail['soal'] }}</p>
	                                					<textarea class="form-control form-control-sm textarea-{{ $data->nomor }}" name="c[{{ $data->nomor }}]" rows="1" placeholder="Jawaban Anda..."></textarea>
	                                				@elseif($paket->tipe_soal == 'number')
	                                            		<p>{{ $detail['soal'] }}</p>
	                                					@for($i=0; $i<10; $i++)
	                                					<div class="form-check form-check-inline">
															<input class="form-check-input checkbox-number-{{ $data->nomor }}" type="checkbox" name="c[{{ $data->nomor }}][]" id="number-{{ $data->nomor }}-{{ $i }}" value="{{ $i }}">
															<label class="form-check-label" for="number-{{ $data->nomor }}-{{ $i }}">{{ $i }}</label>
														</div>
														@endfor
	                                				@elseif($paket->tipe_soal == 'image')
	                                					<p><img width="125" src="{{ asset('assets/images/tes/ist/'.$detail['soal']) }}"></p>
	                                					<p>Pilih Jawaban:</p>
		                                            	@foreach($detail['pilihan'] as $opt=>$pilihan)
		                                                <div class="radio-image d-md-inline mr-md-3" data-num="{{ $data->nomor }}">
		                                                    <input type="radio" class="custom-control-input radio-{{ $data->nomor }} d-none"
		                                                        id="choice-{{ $data->nomor }}-{{ $opt }}" data-num="{{ $data->nomor }}" name="c[{{ $data->nomor }}]"
		                                                        value="{{ $opt }}">
		                                                    <label for="choice-{{ $data->nomor }}-{{ $opt }}" class="border">
		                                                    	<img width="100" src="{{ asset('assets/images/tes/ist/'.$pilihan) }}">
		                                                    </label>
		                                                </div>
		                                                @endforeach
	                                    			@endif
	                                            </td>
	                                        </tr>
	                                    </table>
	                                </div>
	                            </div>
	                        </div>
	                        @endforeach
	                    @endif
					</div>
				</div>
			</form>
    	</div>
	</div>
	<nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
		<ul class="navbar nav mr-auto">
			<li class="nav-item mr-3">
				<a href="#" class="text-secondary"><i class="fa fa-clock-o" style="font-size: 1.5rem"></i></a>
			</li>
			<li class="nav-item">
				<span id="timer">00 : 00 : 00</span>
			</li>
		</ul>
		<ul class="navbar nav ml-auto">
			<li class="nav-item">
				<span id="answered">0</span>/<span id="total"></span> Soal Terjawab
			</li>
			<li class="nav-item ml-3">
				<a href="#" class="text-secondary" data-toggle="modal" data-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
			</li>
			@if($paket->id_paket < $last_part->id_paket)
			<li class="nav-item ml-3">
				<button class="btn btn-md btn-primary text-uppercase" id="btn-next" disabled>Selanjutnya</button>
			</li>
			@endif
			<li class="nav-item ml-3">
				<button class="btn btn-md btn-primary text-uppercase" id="btn-submit" disabled>Submit</button>
			</li>
		</ul>
	</nav>
	<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">
                        <span class="bg-warning rounded-1 text-center px-3 py-2 mr-2"><i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i></span> 
                        Tutorial Tes
                    </h5>
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          			<span aria-hidden="true">&times;</span>
	        		</button>
	      		</div>
		      	<div class="modal-body">
		      		{!! $paket->deskripsi_paket !!}
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-dismiss="modal">MENGERTI</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	var time = "{{ $paket->waktu_pengerjaan }}"; // Time in seconds
	var timeIsAlreadyRun = false;
	var runTime;

	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    totalQuestion();
		$("#timer").text(timeToString(time));
	});

	$("#tutorialModal").on("hidden.bs.modal", function(e){
		if(timeIsAlreadyRun == false){
			runTime = window.setInterval(timer, 1000);
			timeIsAlreadyRun = true;
		}
	});

	function timeToString(time){
		var h = Math.floor(time / 3600) < 10 ? '0' + Math.floor(time / 3600) : Math.floor(time / 3600);
		var m = Math.floor(time / 60) % 60 < 10 ? '0' + Math.floor(time / 60) % 60 : Math.floor(time / 60) % 60;
		var s = (time % 60) < 10 ? '0' + (time % 60) : (time % 60);
		return h + ' : ' + m + ' : ' + s;
	}

	// Timer
	function timer(){
		time--;
		$("#timer").text(timeToString(time));
		if(time <= 10) $("#timer").addClass("text-danger"); // Colorize timer text to be red
		// If time is over
		if(time == 0){
			$("#btn-next").removeAttr("disabled");
			$("#btn-submit").removeAttr("disabled");
			window.clearInterval(runTime); // Stop interval
		}
	}

	// Change value
	$(document).on("change", "input[type=radio], textarea.form-control, input[type=checkbox]", function(){
		// Count answered question
		countAnswered();
		// Check if type is image
		if($(this).parents(".radio-image").length > 0){
			var id = $(this).attr("id");
			var num = $(this).data("num");
			$(".radio-image[data-num="+num+"]").each(function(key,elem){
				$(elem).find("label").removeClass("border-primary");
				$(elem).find("label[for="+id+"]").addClass("border-primary");
			});
		}
	});

	// Count answered question
	function countAnswered(){
		var total = 0;
		$(".num").each(function(key,elem){
			var id = $(this).data("id");
			if($("input[type=radio]").length > 0){
				var value = $(".radio-" + id + ":checked").val();
				value != undefined ? total++ : "";
			}
			else if($("textarea.form-control").length > 0){
				var value = $(".textarea-" + id).val();
				if($.trim(value) != "")	total++;
				else $(".textarea-" + id).val(null);
			}
			else if($("input[type=checkbox]").length > 0){
				var value = $(".checkbox-number-" + id + ":checked").val();
				value != undefined ? total++ : "";
			}
		});
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		if($("input[type=radio]").length > 0){
			var question = $("input[type=radio]").length;
			var pointPerQuestion = 5;
			var total = question / pointPerQuestion;
		}
		else if($("textarea.form-control").length > 0){
			var question = $("textarea.form-control").length;
			var total = question;
		}
		else if($("input[type=checkbox]").length > 0){
			var question = $("input[type=checkbox]").length;
			var pointPerQuestion = 10;
			var total = question / pointPerQuestion;
		}
		$("#total").text(total);
		return total;
	}
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
	.radio-image label {cursor: pointer;}
	.radio-image label.border-primary {border-color: var(--color-1)!important;}
</style>
@endsection