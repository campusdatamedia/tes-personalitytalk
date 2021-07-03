@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>Tes Inyong Set Tah (IST)</h3>
        <hr class="rounded-2" style="border-top: 5px solid rgba(255,255,255,.3)">
        <p class="m-0"><b>SOAL 1-20</b> : Soal ini terdiri atas kalimat-kalimat. Pada setiap kalimat dihilangkan satu kata dan disediakan 5 (lima) kata pilihan.<br>Pilihlah kata yang tepat yang dapat melengkapkan kalimat itu!</p>
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
			    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
			    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
				<div class="">
					<div class="row">
						@foreach($soal as $num=>$data)
						@php $detail = $data->soal[0]; @endphp
					    <div class="col-md-6">
                            <div class="card soal rounded-1 mb-3">
                      			<div class="card-header bg-transparent">
					    			<span class="num font-weight-bold"><i class="fa fa-edit"></i> Soal {{ $data->nomor }}</span>
					    		</div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td>
                                            	<p>{{ $detail['soal'] }}</p>
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
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
				<button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
			</li>
		</ul>
	</nav>
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	var time = 0;

	$(document).ready(function(){
	    totalQuestion();
	    window.setInterval(timer, 1000);
	});

	// Timer
	function timer(){
		time++;
		var h = Math.floor(time / 3600) < 10 ? '0' + Math.floor(time / 3600) : Math.floor(time / 3600);
		var m = Math.floor(time / 60) % 60 < 10 ? '0' + Math.floor(time / 60) % 60 : Math.floor(time / 60) % 60;
		var s = (time % 60) < 10 ? '0' + (time % 60) : (time % 60);
		$("#timer").text(h + ' : ' + m + ' : ' + s);
	}

	// Change value
	$(document).on("change", "input[type=radio]", function(){
		// Count answered question
		countAnswered();

		// Enable submit button
		countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Submit form
	$(document).on("click", "#btn-submit", function(e){
		e.preventDefault();
		$("#form")[0].submit();
	});

	// Count answered question
	function countAnswered(){
		var total = 0;
		$(".num").each(function(key, elem){
			var value = $(".radio-" + (key+1) + ":checked").val();
			value != undefined ? total++ : "";
		});
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = $("input[type=radio]").length;
		var pointPerQuestion = 5;
		var total = totalRadio / pointPerQuestion;
		$("#total").text(total);
		return total;
	}
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
</style>
@endsection