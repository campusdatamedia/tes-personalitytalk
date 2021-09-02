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

	<div id="question" class="row" style="margin-bottom: 100px;">
		<!-- Button Navigation -->
		<div class="col-md-3 mb-3 mb-md-0">
			<div class="card">
				<div class="card-header fw-bold text-center">Navigasi Soal</div>
				<div class="card-body">
				</div>
			</div>
		</div>

		<!-- Question -->
		<div class="col-md-9">
			<div class="card card-question">
				<div class="card-header">
					<span class="fw-bold"><i class="fa fa-edit"></i> <span class="question-title">Soal</span></span>
				</div>
				<div class="card-body">
					<p class="question-text"></p>
					<div class="question-choices"></div>
				</div>
				<div class="card-footer bg-white text-center">
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
	class App extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				part: 1,
				packet: 14,
				items: [],
				firstItem: '',
				lastItem: '',
				activeItem: '',
				activeNumber: 0,
				answers: [],
				doubts: []
			};
			this.handleButtonNavCallback = this.handleButtonNavCallback.bind(this);
			this.handleCardCallback = this.handleCardCallback.bind(this);
		}

		componentDidMount = () => {
			// Fetch data
			fetch('/api/question/all?part=' + this.state.part + '&packet=' + this.state.packet)
				.then(response => response.json())
				.then(data => {
						this.setState({
							items: data.questions,
							firstItem: data.first_question,
							lastItem: data.last_question,
							activeItem: data.questions[0],
							activeNumber: data.questions[0].nomor
						});
					}
				);
		}

		handleButtonNavCallback = (data) => {
			this.setState({
				activeItem: this.getItemByNumber(data.activeNumber),
				activeNumber: data.activeNumber,
			});
		}

		handleCardCallback = (data) => {
			this.setState({
				answers: data.answers,
				doubts: data.doubts,
			});

			if(data.activeNumber !== undefined) {
				this.setState({
					activeItem: this.getItemByNumber(data.activeNumber),
					activeNumber: data.activeNumber
				});
			}
		}

		getItemByNumber = (number) => {
			const {items} = this.state;
			let item;

			if(items.length > 0) {
				for(let i = 0; i < items.length; i++) {
					if(number === items[i].nomor) {
						item = items[i];
					}
				}
			}

			return item;
		}

		getPreviousItem = () => {
			const {items, activeNumber} = this.state;
			let item;

			if(items.length > 0) {
				for(let i = 0; i < items.length; i++) {
					if((activeNumber - 1) === items[i].nomor) {
						item = items[i];
					}
				}
			}

			return item;
		}

		getNextItem = () => {
			const {items, activeNumber} = this.state;
			let item;

			if(items.length > 0) {
				for(let i = 0; i < items.length; i++) {
					if((activeNumber + 1) === items[i].nomor) {
						item = items[i];
					}
				}
			}

			return item;
		}

		render = () => {
			const {items, firstItem, activeItem, activeNumber, answers, doubts} = this.state;

			return (
				<React.Fragment>
					<div class="col-md-3 mb-3 mb-md-0" id="nav-button">
						<ButtonNav
							parentCallback={this.handleButtonNavCallback}
							items={items}
							activeNumber={activeNumber}
							answers={answers}
							doubts={doubts}
						/>
					</div>
					<div class="col-md-9">
						<Card
							parentCallback={this.handleCardCallback}
							item={activeItem}
							previousItem={this.getPreviousItem()}
							nextItem={this.getNextItem()}
						/>
					</div>
				</React.Fragment>
			);
		}
	}

	class ButtonNav extends React.Component {
		constructor(props) {
			super(props);
			this.handleClick = this.handleClick.bind(this);
		}

		handleClick = (number) => {
			// Callback to parent component
			this.props.parentCallback({
				activeNumber: number
			});
		}

		render = () => {
			const items = this.props.items;
			const activeNumber = this.props.activeNumber;
			const answers = this.props.answers;
			const doubts = this.props.doubts;

			return (
				<div class="card">
					<div class="card-header fw-bold text-center">Navigasi Soal</div>
					<div class="card-body">
						{
							items.map((item, index) => {
								// Set button color
								let buttonColor;
								if(doubts[item.nomor] === true) buttonColor = 'btn-warning';
								else if(answers[item.nomor] !== undefined) buttonColor = 'btn-primary';
								else if(item.nomor === activeNumber && answers[item.nomor] === undefined) buttonColor = 'btn-info';
								else buttonColor = 'btn-outline-dark';

								return (
									<button
										className={`btn btn-sm ${buttonColor}`}
										onClick={() => this.handleClick(item.nomor)}
									>
										{item.nomor} ({answers[item.nomor] !== undefined ? answers[item.nomor] : '-' })
									</button>
								);
							})
						}
					</div>
				</div>
			);
		}
	}

	class Card extends React.Component {
		constructor(props) {
			super(props);
			this.state = {
				answers: [],
				doubts: []
			}
			this.handleChoiceCallback = this.handleChoiceCallback.bind(this);
			this.handleButtonDoubtCallback = this.handleButtonDoubtCallback.bind(this);
		}

		handleChoiceCallback = (data) => {
			let {answers, doubts} = this.state;
			answers[data.number] = data.answer;

			// Update state
			this.setState({
				answers: answers
			});

			// Callback to parent component
			this.props.parentCallback({
				answers: answers,
				doubts: doubts
			})
		}

		handleButtonDoubtCallback = (data) => {
			let {answers, doubts} = this.state;
			doubts[data.number] = data.doubt;

			// Update state
			this.setState({
				doubts: doubts
			});

			// Callback to parent component
			this.props.parentCallback({
				answers: answers,
				doubts: doubts
			});
		}

		handleButtonPreviousCallback = (data) => {
			let {answers, doubts} = this.state;

			// Callback to parent component
			this.props.parentCallback({
				answers: answers,
				doubts: doubts,
				activeNumber: data.number
			});
		}

		render = () => {
			const {answers, doubts} = this.state;
			const item = this.props.item;
			const question = this.props.item.soal;
			const choices = question !== undefined ? Object.entries(question[0].pilihan) : [];

			return (
				<div class="card card-question">
					<div class="card-header">
						<span class="fw-bold"><i class="fa fa-edit"></i> Soal {item.nomor}</span>
					</div>
					<div class="card-body">
						<p class="question-text">{question !== undefined ? question[0].soal : ''}</p>
						<div class="question-choices">
							{
								choices.map((choice) => {
									return (
										<Choice
											parentCallback={this.handleChoiceCallback}
											number={item.nomor}
											option={choice[0]}
											description={choice[1]}
											isChecked={answers[item.nomor] === choice[0] ? true : false}
										/>
									)
								})
							}
						</div>
					</div>
					<div class="card-footer bg-white text-center">
						<ButtonPrevious
							parentCallback={this.handleButtonPreviousCallback}
							number={this.props.previousItem !== undefined ? this.props.previousItem.nomor : 0}
						/>
						<ButtonDoubt
							parentCallback={this.handleButtonDoubtCallback}
							number={item.nomor}
							isDoubt={doubts[item.nomor] ? true : false}
						/>
						<button class="btn btn-sm btn-primary mx-1">Selanjutnya <i class="fa fa-chevron-right"></i></button>
					</div>
				</div>
			);
		}
	}

	class Choice extends React.Component {
		constructor(props) {
			super(props);
			this.handleChange = this.handleChange.bind(this);
		}

		handleChange = (number, answer) => {
			// Callback to parent component
			this.props.parentCallback({
				number: number,
				answer: answer,
			});
		}

		render = () => {
			return (
				<div class="form-check">
					<input
						class="form-check-input"
						type="radio"
						name={`choice[${this.props.number}]`}
						id={`choice-${this.props.option}`}
						value={this.props.option}
						checked={this.props.isChecked}
						onChange={() => this.handleChange(this.props.number, this.props.option)}
					/>
					<label class="form-check-label" for={`choice-${this.props.option}`}>{this.props.description}</label>
				</div>
			);
		}
	}

	class ButtonDoubt extends React.Component {
		constructor(props) {
			super(props);
			this.handleClick = this.handleClick.bind(this);
		}

		handleClick = (number) => {
			// Callback to parent component
			this.props.parentCallback({
				number: number,
				doubt: !this.props.isDoubt,
			});
		}

		render = () => {
			return (
				<button
					class="btn btn-sm btn-warning mx-1"
					onClick={() => this.handleClick(this.props.number)}
				>
					<i class="fa fa-lightbulb-o me-1"></i>
					{this.props.isDoubt ? 'Yakin' : 'Ragu'}
				</button>
			);
		}
	}

	class ButtonPrevious extends React.Component {
		constructor(props) {
			super(props);
		}

		handleClick = (number) => {
			// Callback to parent component
			this.props.parentCallback({
				number: number
			});
		}

		render = () => {
			return (
				<button
					class="btn btn-sm btn-primary mx-1"
					onClick={() => this.handleClick(this.props.number)}
				>
					<i class="fa fa-chevron-left"></i> Sebelumnya
				</button>
			);
		}
	}

	// Render DOM
	ReactDOM.render(<App/>, document.getElementById('question'));
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
	#nav-button .btn:focus {box-shadow: none;}
</style>
@endsection