@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
	<h3 class="m-0 text-center text-white">{{ $paket->nama_paket }}</h3>
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
				<div class="container-fluid">
					<div class="row">
					    @foreach($soal as $key=>$data)
                            <div class="w-100 mb-3">
                                <div class="card soal rounded-1">
                                	<div class="card-header bg-transparent">
			    						<span class="num font-weight-bold"><i class="fa fa-edit"></i> Soal {{ $key+1 }}</span>
                                	</div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input radio radio{{ $key+1 }}" id="customRadio{{ $key+1 }}a" name="jawaban[{{ $key+1 }}]"
                                                        value="{{ $data['jawabanA'] }}">
                                                        <label class="custom-control-label" for="customRadio{{ $key+1 }}a">
                                                            <h5>{!! $data['soalA'] !!}</h5>
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-radio mt-2">
                                                        <input type="radio" class="custom-control-input radio radio{{ $key+1 }}" id="customRadio{{ $key+1 }}b" name="jawaban[{{ $key+1 }}]"
                                                        value="{{ $data['jawabanB'] }}">
                                                        <label class="custom-control-label" for="customRadio{{ $key+1 }}b">
                                                            <h5>{!! $data['soalB'] !!}</h5>
                                                        </label>
                                                    </div>
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
		<ul class="navbar nav ml-auto">
			<li class="nav-item">
				<span id="answered">0</span>/<span id="total"></span> Soal Terjawab
			</li>
			<li class="nav-item ml-3">
				<a href="#" class="text-secondary" data-toggle="modal" data-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
			</li>
			<li class="nav-item ml-3">
				<button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
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
		      	<div class="modal-body text-justify">
		      	    <p>Anda diberikan 90 pasangan pernyataan. Pilihlah <b><i>satu pernyataan</i></b> dari pasangan pernyataan itu yang Anda rasakan <b><i>paling mendekati gambaran</i></b> diri Anda, atau yang <b><i>paling menunjukan</i></b> perasaan Anda.<br>
                    Kadang-kadang Anda merasa bahwa kedua pernyataan itu tidak sesuai benar dengan diri Anda, namun demikian Anda diminta tetap memilih <b><i>satu pernyataan</i></b> yang paling menunjukan diri Anda.</p>
		        	<p>Cara menjawab:</p>
		        	<p>Anda diminta untuk memilih salah satu pernyataan yang paling sesuai dengan diri Anda , Atau paling mungkin Anda lakukan.</p>
		        	<p>Anda dapat memilih jawaban dengan cara mengeklik pilihan yang terdapat di depan pernyataan yang akan Anda pilih. Misalnya:</p>
		        	<form>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="customRadioContoh1a" name="contoh1"
                            value="soalcontoh1a">
                            <label class="custom-control-label" for="customRadioContoh1a">
                                <p>Saya seorang pekerja <i><u>“keras”</u></i></p>
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="customRadioContoh1b" name="contoh1"
                            value="soalcontoh1b">
                            <label class="custom-control-label" for="customRadioContoh1b">
                                <p>Saya <i><u>bukan</u></i> seorang pemurung</p>
                            </label>
                        </div>
                    </form>
                    <p>Disini tidak ada jawaban <b> Benar</b> atau <b> Salah</b>. Apapun yang Anda pilih, hendaknya sungguh-sungguh menggambarkan diri Anda.</p>
                    <p>Bekerjalah dengan cepat, tetapi jangan sampai ada nomor pernyataan yang terlewatkan.</p>
                </div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-dismiss="modal">Mengerti</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    totalQuestion();
	});

	// Change value
	$(document).on("change", ".radio[type=radio]", function(){
		// Count answered question
		countAnswered();

		// Enable submit button
		countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Count answered question
	function countAnswered(){
		var total = 0;
		$(".num").each(function(key, elem){
			var value = $(".radio" + (key+1) + ":checked").val();
			value != undefined ? total++ : "";
		});
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = $(".radio").length;
		var pointPerQuestion = 2;
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