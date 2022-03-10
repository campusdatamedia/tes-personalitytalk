@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <h3 class="m-0 text-center text-white">{{ $packet->name }} 2.0</h3>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
    @if($selection != null)
	    @if(strtotime('now') < strtotime($selection->test_time))
	    <div class="row">
	        <!-- Alert -->
	        <div class="col-12 mb-2">
	            <div class="alert alert-danger fade show text-center" role="alert">
	                Tes akan dilaksanakan pada tanggal <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
	            </div>
	        </div>
	    </div>
	    @endif
    @endif
    @if($selection == null || ($selection != null && strtotime('now') >= strtotime($selection->test_time)))
	<div class="row" style="margin-bottom:100px">
	    <div class="col-12">
            @if(in_array(Auth::user()->attribute->gender, ['L','P']))
		    <form id="form" method="post" action="/tes/{{ $path }}/store">
			    <input type="hidden" name="path" value="{{ $path }}">
			    <input type="hidden" name="packet_id" value="{{ $packet->id }}">
			    <input type="hidden" name="test_id" value="{{ $test->id }}">
        		@csrf
        		<div class="row justify-content-center">
                    @php $letters = ['A','B','C','D','E','F','G','H','I']; @endphp
                    @foreach($questions as $keysoal=>$q)
        			<div class="col-lg-8 mx-auto" style="margin-top: 20px;">
        				<div class="card soal rounded-1">
                            <div class="card-header bg-transparent">
                                <span class="num fw-bold" data-id="{{ $q->number }}"><i class="fa fa-edit"></i> Soal {{ $q->number }}</span>
                            </div>
                            @php
                                $questions_array = json_decode($q->description, true);
                            @endphp
        					<div class="card-body">
                                <p class="text-danger">Tekan dan geser pekerjaan di bawah ini untuk mengurutkannya dari atas sampai bawah!</p>
                                <div class="list-group px-4 px-md-0 sortable" data-id="{{ $q->number }}">
                                    @foreach($questions_array[Auth::user()->attribute->gender] as $key=>$occupation)
                                    <div class="list-group-item">
                                        <span class="num-order fw-bold me-2"></span> {{ $occupation }}
                                        <input type="hidden" name="score[{{ $q->number }}][{{ $key }}]" value="{{ ($key+1) }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
        		</div>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">Tulislah dibawah ini tiga (3) macam pekerjaan yang paling ingin anda lakukan atau paling anda sukai (tidak harus pekerjaan yang tercantum di dalam daftar yang ada):</label>
                            @for($i=1; $i<=3; $i++)
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text">({{ $i }})</span>
                                <input type="text" name="occupations[]" class="form-control">
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
        	</form>
            @else
            <div class="alert alert-danger mb-0">
                Segera hubungi Admin atau panitia tes jika soal tidak muncul.
            </div>
            @endif
    	</div>
	</div>
	<nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
		<div class="container">
			<ul class="navbar nav ms-auto">
				<li class="nav-item">
					<span id="answered">0</span>/<span id="total"></span> Soal Terjawab
				</li>
				<li class="nav-item ms-3">
					<a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
				</li>
				<li class="nav-item ms-3">
					<button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
				</li>
			</ul>
		</div>
	</nav>
	<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">
                        <span class="bg-warning rounded-1 text-center px-3 py-2 me-2"><i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i></span> 
                        Tutorial Tes
                    </h5>
	        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      		</div>
		      	<div class="modal-body">
                    <p>Dibawah ini anda akan menemui daftar-daftar berbagai macam pekerjaan yang tersusun dalam berbagai kelompok. Setiap kelompok terdiri dari 12 macam pekerjaan. Setiap pekerjaan merupakan keahlian khusus yang memerlukan latihan atau pendidikan keahlian sendiri. Mungkin hanya beberapa diantaranya yang anda sukai. Disini anda diminta untuk memilih pekerjaan mana yang ingin anda lakukan atau pekerjaan mana yang anda sukai, terlepas dari besarnya upah gaji yang akan anda terima. Juga terlepas apakah anda berhasil atau tidak dalam mengerjakan pekerjaan tersebut.</p>
                    <p><strong>Tugas anda adalah mengurutkan pekerjaan-pekerjaan berikut berdasarkan besarnya kadar kesukaan/minat anda terhadap pekerjaan itu dari yang disukai sampai yang paling tidak disukai.</strong></p>
                    <p>Selamat bekerja!</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">MENGERTI</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
    @endif
</div>
@endsection

@section('js-extra')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ asset('assets/js/jquery.mjs.nestedSortable.js') }}"></script>
<script src="{{ asset('assets/js/touchpunch.min.js') }}"></script>
<script type="text/javascript">
    let answered = [];

	$(document).ready(function() {
	    sortable(".sortable");
		$("#tutorialModal").modal("toggle");
	    totalQuestion();
	});

	// Count answered questions
	function countAnswered(num) {        
        if(answered.indexOf(num) < 0)
            answered.push(num);
        
        var total = answered.length;
		$("#answered").text(total);
		return total;
	}

	// Total questions
	function totalQuestion() {
		var total = $(".num").length;
		$("#total").text(total);
		return total;
	}

    // Sortable
    function sortable(selector) {
        var sortable = $(selector).sortable({
            items: "> div:not(.ui-state-disabled)",
            placeholder: "ui-state-highlight",
            start: function(event, ui) {
                $(selector).find(".ui-state-highlight").css("height", $(ui.item).outerHeight());
            },
            update: function(event, ui) {
                var items = $(this).children(".ui-sortable-handle");
                $(items).each(function(key, elem) {
                    $(elem).find(".num-order").text(key + 1);
                    $(elem).find("input[type=hidden]").val(key + 1);
                });

                // Count answered
                var id = $(this).data("id");
                countAnswered(id);
                countAnswered(id) >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
            }
        });
        $(selector).disableSelection();
        return sortable;
    }
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
    .table thead tr th {text-align: center;}
    .soal {font-size: 85%;}
    .ui-sortable-handle {cursor: move;}
    .ui-state-highlight {height: 2rem; background-color: #f3eeb5;}
</style>
@endsection