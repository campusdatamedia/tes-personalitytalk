@extends('template/main')

@section('content')

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $paket->nama_paket }}</h3>
        @if($paket->deskripsi_paket != '')
        <hr class="rounded-2" style="border-top: 5px solid rgba(255,255,255,.3)">
        <p class="m-0"><b>SOAL {{ $range_soal }}</b> : <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#tutorialModal"><u>Lihat Petunjuk Pengerjaan Disini</u></a></p>
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
	        <div class="col-12 mb-2">
	            <div class="alert alert-danger fade show text-center" role="alert">
	                Tes akan dilaksanakan pada tanggal <strong>{{ setFullDate($seleksi->waktu_wawancara) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($seleksi->waktu_wawancara)) }}</strong>.
	            </div>
	        </div>
	    </div>
	    @endif
    @endif

	<div class="row" style="margin-bottom: 100px;">
		<!-- Button Navigation -->
		<div class="col-md-3 mb-3 mb-md-0">
			<div class="card">
				<div class="card-header fw-bold text-center">Navigasi Soal</div>
				<div class="card-body">
					<div id="nav-button"></div>
				</div>
			</div>
		</div>

		<!-- Question -->
		<div class="col-md-9">
			<div class="card card-question">
				<div class="card-header">
					<span class="fw-bold"><i class="fa fa-edit"></i> <span class="question-title"></span></span>
				</div>
				<div class="card-body">
					<p class="question-text"></p>
					<div class="question-choices"></div>
				</div>
				<div class="card-footer bg-white text-center">
					<button class="btn btn-sm btn-primary"><i class="fa fa-chevron-left"></i> Sebelumnya</button>
					<button class="btn btn-sm btn-warning"><i class="fa fa-lightbulb-o"></i> Ragu</button>
					<button class="btn btn-sm btn-primary">Selanjutnya <i class="fa fa-chevron-right"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-extra')

<!-- React JS -->
<script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@17/umd/react-dom.production.min.js" crossorigin></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>

<script type="text/babel">
	class ButtonNav extends React.Component {
		// Constructor
		constructor(props) {
			super(props);
			this.state = {
				part: 1,
				packet: 14,
				items: [],
				activeItem: 0
			};
			this.handleClick = this.handleClick.bind(this);
		}

		componentDidMount = () => {
			const {part, packet} = this.state;

			// Fetch data
			fetch('/api/question/all?part=' + part + '&packet=' + packet)
				.then(response => response.json())
				.then(data =>
					this.setState({
						items: data.questions,
						activeItem: data.questions[0].nomor
					})
				);
		}

		handleClick = (e) => {
			// Update state
			this.setState({
				// activeItem: parseInt(e.target.dataset.number)
				activeItem: e
			});
		}

		render = () => {
			const {part, packet, items, activeItem} = this.state;

			return (
				items.map((item, index) => {
					return (
						<button
							key={index}
							className={`btn btn-sm ${item.nomor === activeItem ? 'btn-primary' : 'btn-outline-dark'} btn-question`}
							onClick={() => this.handleClick(item.nomor)}
							data-part={part}
							data-packet={packet}
							// data-number={item.nomor}
						>
							{item.nomor}
						</button>
					);
				})
			);
		}
	}

	// Render
	ReactDOM.render(<ButtonNav/>, document.getElementById('nav-button'));
</script>

<script type="text/javascript">
	$(document).on("click", ".btn-question", function(e){
		e.preventDefault();

		// Retrieve data-id attributes
		var part = $(this).data("part");
		var packet = $(this).data("packet");
		var number = $(this).data("number");

		// AJAX request
		$.ajax({
			type: 'get',
			url: '/api/question',
			data: {path: 'ist', part: part, packet: packet, number: number},
			success: function(response){
				// console.log(response);
				var choices = Object.entries(response.question.soal[0].pilihan);
				var choiceHTML = '';
				$(".card-question").find(".question-title").text("Soal " + response.question.nomor);
				$(".card-question").find(".question-text").text(response.question.soal[0].soal);
				$(choices).each(function(key,choice){
					choiceHTML += '<div class="form-check">';
					choiceHTML += '<input class="form-check-input" type="radio" name="c[' + response.question.nomor + ']" id="choice-' + response.question.nomor + '-' + choice[0] + '" value="' + choice[0] + '">';
					choiceHTML += '<label class="form-check-label" for="choice-' + response.question.nomor + '-' + choice[0] + '">' + choice[1] + '</label>';
	 				choiceHTML += '</div>';
				});
				$(".card-question").find(".question-choices").html(choiceHTML);
			}
		});

		// Change button class
		removeButtonClass(".btn-question", "btn-primary", "btn-outline-dark");
		$(this).addClass("btn-primary").removeClass("btn-outline-dark");
	});

	function removeButtonClass(selector, classToRemove, classToAdd){
		$(selector).each(function(key,elem){
			$(elem).removeClass(classToRemove);
			$(elem).addClass(classToAdd);
		});
	}
</script>

@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px; overflow-y: auto; max-height: calc(100vh - 200px);}
	.table {margin-bottom: 0;}
	.radio-image label {cursor: pointer;}
	.radio-image label.border-primary {border-color: var(--color-1)!important;}
	/* #form {filter: blur(3px);} */

	#nav-button {text-align: center;}
	#nav-button .btn {width: 3.75rem; margin: .25rem;}
</style>
@endsection