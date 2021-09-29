@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $paket->nama_paket }}</h3>
        <hr class="rounded-2" style="border-top: 5px solid rgba(255,255,255,.3)">
        <p class="m-0"><b>ITEM 1-20</b> : Saat anda memberikan 10 poin pada masing-masing dari sepuluh pernyataan di bawah ini, berpikir tentang situasi di tempat kerja, di sekolah, di rumah, dan bersama teman-teman, tetapi selalu berpikir tentang situasi……</br><b>di mana segala sesuatu tak beres dan anda berkonflik dengan orang lain.</b></p>
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
                <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
			    <input type="hidden" name="path" value="{{ $path }}">
			    <input type="hidden" name="id_paket" value="{{ $paket->id_paket }}">
			    <input type="hidden" name="id_tes" value="{{ $paket->id_tes }}">
                @csrf
                @php $nomor = 0; @endphp
                @foreach ($soal1 as $value)
                @php $nomor++; @endphp
                <div class="card soal rounded-1 mb-3">
                    <div class="card-header bg-transparent">
                        <span class="fw-bold fst-italic num"><i class="fa fa-edit"></i> Soal {{$nomor}}. {{$value['header']}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val1']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class=""> {{$value['soal1']}} </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val2']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class="">{{$value['soal2']}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val3']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class="">{{$value['soal3']}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach                        
                    
                @foreach ($soal2 as $value)
                @php $nomor++; @endphp
                <div class="card soal rounded-1 mb-3">
                    <div class="card-header bg-transparent">
                        <span class="fw-bold fst-italic num"><i class="fa fa-edit"></i> Soal {{$nomor}}. {{$value['header']}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val1']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class=""> {{$value['soal1']}} </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val2']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class="">{{$value['soal2']}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table>
                                    <tr>
                                        <td valign="top"><input type="text" name="{{$value['val3']}}" class="ket-{{$nomor}}"></td>
                                        <td valign="top">
                                            <p class="">{{$value['soal3']}}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </form>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
        <div class="container">
            <div class="alert fade show text-center mb-0 ms-md-3 col-md" role="alert" id="ket" style="display:none;">
            </div>
            <ul class="navbar nav ms-auto me-auto me-md-0">
                <li class="nav-item">
                    <span id="answered">0</span>/<span id="total"></span> Soal Terjawab
                </li>
                <li class="nav-item ms-3">
                    <button class="btn btn-md btn-primary text-uppercase" id="btn-submit" disabled>Submit</button>
                </li>
            </ul>
        </div>
	</nav>
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	// vertical align modal
	$(document).ready(function(){
	    totalQuestion();
	});

	// Total question
	function totalQuestion(){
		var totalRadio = $("input:text").length;
		var pointPerQuestion = 3;
		var total = totalRadio / pointPerQuestion;
		$("#total").text(total);
		return total;
	}
	
	// SDI Form
    $("input").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
    $(document).on("input", "input:text", function () {
        var strClass = $(this).prop("class");
        var intTotal = 0;
        $.each($("input:text." + strClass), function () {
            var intInputValue = parseInt($(this).val());
            if (!isNaN(intInputValue)) {
                intTotal = intTotal + intInputValue;
            }
        });
        $('.hasil').val(intTotal);
        for (i = 1; i < 4; i++) {
            $('.ket-' + i).keyup(function () {
                if ($(this).val() > 10) {
                    alert("maksimal 10");
                    $(this).val('');
                    $('.hasil').val('');
                }
            });
            var x = document.getElementById("ket");
            // var x = $("#ket");
            if (intTotal == 10) {
                // x.innerHTML = "<div class='alert alert-success'><strong>Benar!</strong></div>";
                x.innerHTML = "<strong>Benar!</strong>";
                x.style.display = "block";
                x.classList.remove("alert-danger");
                x.classList.add("alert-success");
                // break;
            } else {
                // x.innerHTML = "<div class='alert alert-danger'><strong>Jumlah kurang atau lebih dari 10!</strong></div>";
                x.innerHTML ="<strong>Jumlah kurang atau lebih dari 10!</strong>";
                x.style.display = "block";
                x.classList.remove("alert-success");
                x.classList.add("alert-danger");
                // break;
            }
        }
        var total = 0;
        $.each($("input:text"), function (key, elem) {
            var amount = 0;
            var className = $(elem).prop("class");
            var array = [];
            $.each($("input:text." + className), function (key2, elem2) {
                var intInputValue = parseInt($(elem2).val());
                if (!isNaN(intInputValue)) {
                    amount = amount + intInputValue;
                    array.push(intInputValue);
                }
            });
            if(amount == 10 && array.length == 3){
                total++;
            }
        });
        total = total / 3;
        $("#answered").text(total);
        total >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    });
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
	
	input[type="text"] {width: 50px; height: 50px; text-align: center; margin-top: 5px;}
    td {padding: 5px;}
    @media(max-width: 420px) { #konten {margin-top: 120px;} }
    @media (min-width: 576px) { .jumbotron {padding: 2rem 1rem;} }
    /*.alert {margin-bottom: 0;}*/
</style>
@endsection