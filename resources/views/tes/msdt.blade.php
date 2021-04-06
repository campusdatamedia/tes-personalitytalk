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
                    <h5 class="m-0 font-weight-bold text-dark text-center">Tes Management Style Diagnostic Test (MSDT)</h5>
                </div>
    			<div class="card-body">
    				<form id="form" method="post" action="/tes/{{ $path }}/store">
    				    {{ csrf_field() }}
    				    <input type="hidden" name="path" value="{{ $path }}">
    				    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
    				    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
    					<div class="container-fluid">
    						<div class="row">
    						    @foreach($soal->soal as $data)
    						    <div class="col-12 mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="50">
                                                        <h5 class="num">{{$data['id']}}</h5>
                                                    </td>
                                                    <td>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input radio{{$data['id']}}"
                                                                id="customRadio{{$data['id']}}a" name="p[{{$data['id']}}]"
                                                                value="A">
                                                            <label class="custom-control-label text-justify" for="customRadio{{$data['id']}}a">
                                                                <span>
                                                                    {{$data['pilihan1']}}
                                                                </span>
                                                            </label>
                                                        </div>
                    
                                                        <div class="custom-control custom-radio mt-3">
                                                            <input type="radio" class="custom-control-input radio{{$data['id']}}"
                                                                id="customRadio{{$data['id']}}b" name="p[{{$data['id']}}]"
                                                                value="B">
                                                            <label class="custom-control-label text-justify" for="customRadio{{$data['id']}}b">
                                                                <span>
                                                                    {{$data['pilihan2']}}
                                                                </span>
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
		      	    <p>Pada tes ini, Anda akan membaca sejumlah pernyataan mengenai tindakan yang mungkin Anda lakukan dalam tugas Anda di perusahaan.</p>
		        	<p>Tes ini terdiri dari 64 Soal dan 1 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit.</p>
		        	<p>Anda diminta untuk memilih salah satu pernyataan yang paling sesuai dengan diri Anda , Atau paling mungkin Anda lakukan.</p>
		        	<p style="margin-bottom: .5rem;"><strong>Perhatikan contoh berikut:</strong></p>
		        	<ul style="list-style: upper-alpha; font-weight: bold; padding-left: 2rem;">
		        		<li>Saya datang ke kantor lebih awal bila sedang banyak pekerjaan</li>
		        		<li>Saya bersedia bekerja lembur bila tugas saya belum selesai</li>
		        	</ul>
		        	<p>Manakah dari dua pernyataan tersebut yang paling mungkin Anda lakukan. Jika Anda lebih memilih datang lebih awal daripada bekerja lembur maka pilihlah pernyataan <strong>A</strong>. Tetapi bila Anda lebih memilih bekerja lembur , maka pilihlah <strong>B</strong>.</p>
		        	<p>Karena kedua pernyataan selalu disajikan berpasangan, mungkin saya Anda memilih pernyataan <strong>A</strong> maupun <strong>B</strong> sekaligus. Dalam hal ini , Anda tetap diminta untuk hanya memilih satu pernyataan.</p>
		      	    <p>Ini bukan suatu tes. Disini tidak ada jawaban “benar” atau “salah”. Apapun yang Anda pilih , hendaknya sungguh-sungguh menggambarkan diri Anda.</p>
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
			var value = $(".radio" + (key+1) + ":checked").val();
			value != undefined ? total++ : "";
		});
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = $("input[type=radio]").length;
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