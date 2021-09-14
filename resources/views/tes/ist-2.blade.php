@extends('template/main')

@section('content')

<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $paket->nama_paket }}</h3>
		<p class="m-0"><a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#tutorialModal"><u>Lihat Petunjuk Pengerjaan Disini</u></a></p>
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
				<div class="card-body"></div>
			</div>
		</div>

		<!-- Card Question -->
		<div class="col-md-9">
			<div class="card card-question">
				<div class="card-header">
					<span class="fw-bold"><i class="fa fa-edit"></i> Soal</span>
				</div>
				<div class="card-body"></div>
				<div class="card-footer bg-white text-center"></div>
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
			test: 'ist',
			part: 1,
			parts: [],
			items: [],
			activeItem: '',
			activeNumber: 0,
			answers: [],
			doubts: [],
			timeIsRunning: false
		};
	}

	componentDidMount = () => {
		this.getRequest(this.state.test, this.state.part);
	}

	getRequest = (test, part) => {
		// Fetch data
		fetch('/api/question?test=' + test + '&part=' + part)
			.then(response => response.json())
			.then(data => {
					this.setState({
						parts: data.parts,
						items: data.questions,
						activeItem: data.questions.length > 0 ? data.questions[0] : {},
						activeNumber: data.questions.length > 0 ? data.questions[0].nomor : null
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

		if(data.part !== undefined) {
			this.getRequest(this.state.test, data.part);
			this.setState({
				part: data.part
			});
		}

		if(data.timeIsRunning !== undefined) {
			this.setState({
				timeIsRunning: data.timeIsRunning
			});
		}
	}

	handleModalCallback = (data) => {
		const {timeIsRunning} = this.state;

		if(timeIsRunning === false) {
			this.setState({
				timeIsRunning: data.timeIsRunning
			})
		}
	}

	getNextPart = () => {
		const {parts, part} = this.state;
		let key;

		if(parts.length > 0) {
			for(let i = 0; i < parts.length; i++) {
				if(part === parts[i].part) {
					key = i;
				}
			}
		}

		return parts[key + 1];
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
		const {items, activeItem, activeNumber, answers, doubts, timeIsRunning} = this.state;

		return (
			<React.Fragment>
				<ModalAuth/>
				<ModalTutorial
					parentCallback={this.handleModalCallback}
					part={activeItem.part}
					item={activeItem}
				/>
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
						nextPart={this.getNextPart()}
						timeIsRunning={timeIsRunning}
					/>
				</div>
			</React.Fragment>
		);
	}
}

class ModalAuth extends React.Component {
	constructor(props) {
		super(props);
	}

	componentDidMount = () => {
		// Get elements
		let myModal = document.getElementById("modalAuth");
		let myInput = document.getElementById("inputToken");

		// Show modal
		let modal = new bootstrap.Modal(myModal);
		modal.show();

		// Add event when modal shows
		myModal.addEventListener('shown.bs.modal', () => {
			document.body.classList.add("modal-auth");
			myInput.focus();
		});
	}

	handleSubmit = (event) => {
		event.preventDefault();
		window.removeEventListener("beforeunload", j);
		let token = document.getElementById("inputToken").value;
	}

	render = () => {
		return (
			<div class="modal fade" id="modalAuth" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<form id="formAuth" onSubmit={this.handleSubmit}>
							<div class="modal-header bg-info">
								<h5 class="modal-title" id="exampleModalLabel">Autentikasi</h5>
							</div>
							<div class="modal-body">
								<div class="m-0">
									<label class="form-label">Masukkan Kode:</label>
									<input type="text" name="token" class="form-control" id="inputToken" required/>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		);
	}
}

class ButtonNav extends React.Component {
	constructor(props) {
		super(props);
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
							else if(answers[item.nomor] !== undefined && (((item.tipe_soal === 'choice' || item.tipe_soal === 'image') && answers[item.nomor] !== null) || (item.tipe_soal === 'essay' && answers[item.nomor] !== '') || (item.tipe_soal === 'number' && answers[item.nomor].length > 0))) buttonColor = 'btn-primary';
							else if(item.nomor === activeNumber && (answers[item.nomor] === undefined || answers[item.nomor] === null || answers[item.nomor] === '' || answers[item.nomor].length === 0)) buttonColor = 'btn-info';
							else buttonColor = 'btn-outline-dark';

							// Set button note
							let buttonNote = '-';
							if(answers[item.nomor] !== undefined) {
								if(item.tipe_soal === 'choice' && answers[item.nomor] !== null) buttonNote = answers[item.nomor];
								else if(item.tipe_soal === 'essay' && answers[item.nomor] !== '') buttonNote = 'Y';
								else if(item.tipe_soal === 'number' && answers[item.nomor].length > 0) buttonNote = 'Y';
								else if(item.tipe_soal === 'image' && answers[item.nomor] !== null) buttonNote = answers[item.nomor];
							}

							return (
								<button
									class={`btn btn-sm ${buttonColor}`}
									onClick={() => this.handleClick(item.nomor)}
								>
									{item.nomor} ({buttonNote})
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
		};
	}

	handleTimerCallback = (data) => {
		let {answers, doubts} = this.state;

		// Callback to parent component
		this.props.parentCallback({
			answers: answers,
			doubts: doubts,
			part: data.part,
			timeIsRunning: false
		});
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
		});
	}

	handleCheckboxNumberCallback = (data) => {
		let {answers, doubts} = this.state;
		let answerTemp = answers[data.number];

		// Define answer to array
		if(answerTemp === undefined) {
			answerTemp = [];
		}

		// If checkbox is checked
		if(data.isChecked) {
			// If value is not in answer array
			if(answerTemp.indexOf(data.value) < 0) {
				answerTemp.push(data.value);
				answers[data.number] = answerTemp;
			}
		}
		// If checkbox is not checked
		else {
			// If value is in answer array
			if(answerTemp.indexOf(data.value) >= 0) {
				answerTemp.splice(answerTemp.indexOf(data.value), 1);
				answers[data.number] = answerTemp;
			}
		}

		// Update state
		this.setState({
			answers: answers
		});

		// Callback to parent component
		this.props.parentCallback({
			answers: answers,
			doubts: doubts
		});
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

	handleButtonNextCallback = (data) => {
		let {answers, doubts} = this.state;

		// Callback to parent component
		this.props.parentCallback({
			answers: answers,
			doubts: doubts,
			activeNumber: data.number
		});
	}

	handleButtonSubmitCallback = (data) => {
		let {answers, doubts} = this.state;

		// Callback to parent component
		this.props.parentCallback({
			answers: answers,
			doubts: doubts,
			part: data.part,
			timeIsRunning: false
		});
	}

	renderForm = () => {
		const {answers, doubts} = this.state;
		const item = this.props.item;
		const question = this.props.item.soal;
		const choices = (question !== undefined && question[0].pilihan !== undefined) ? Object.entries(question[0].pilihan) : [];

		if(item.tipe_soal === 'choice') {
			return (
				<React.Fragment>
					<p>{item.soal !== undefined ? item.soal[0].soal : ''}</p>
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
				</React.Fragment>
			);
		}
		else if(item.tipe_soal === 'essay') {
			return (
				<React.Fragment>
					<p>{item.soal !== undefined ? item.soal[0].soal : ''}</p>
					<TextField
						parentCallback={this.handleChoiceCallback}
						number={item.nomor}
						value={answers[item.nomor] !== undefined ? answers[item.nomor] : ''}
					/>
				</React.Fragment>
			);
		}
		else if(item.tipe_soal === 'number') {
			return (
				<React.Fragment>
					<p>{item.soal !== undefined ? item.soal[0].soal : ''}</p>
					<CheckboxNumber
						parentCallback={this.handleCheckboxNumberCallback}
						number={item.nomor}
						checkeds={answers[item.nomor] !== undefined ? answers[item.nomor] : []}
					/>
				</React.Fragment>
			);
		}
		else if(item.tipe_soal === 'image') {
			return (
				<React.Fragment>
					<p><img width="125" src={`/assets/images/tes/ist/${item.soal !== undefined ? item.soal[0].soal : ''}`}/></p>
					<p>Pilih Jawaban:</p>
					{
						choices.map((choice) => {
							return (
								<ImageChoice
									parentCallback={this.handleChoiceCallback}
									number={item.nomor}
									option={choice[0]}
									description={choice[1]}
									isChecked={answers[item.nomor] === choice[0] ? true : false}
								/>
							)
						})
					}
				</React.Fragment>
			);
		}
		else return null;
	}

	render = () => {
		const {answers, doubts} = this.state;
		const item = this.props.item;

		return (
			<div class="card card-question">
				<div class="card-header d-flex justify-content-between">
					<span class="fw-bold"><i class="fa fa-edit"></i> Soal {item.nomor}</span>
					<span>
						<Timer
							parentCallback={this.handleTimerCallback}
							part={item.part}
							time={item.waktu_pengerjaan}
							timeIsRunning={this.props.timeIsRunning}
							nextPart={this.props.nextPart !== undefined ? this.props.nextPart.part : 0}
						/>
					</span>
				</div>
				<div class="card-body">
					{this.renderForm()}
				</div>
				<div class="card-footer bg-white d-md-flex justify-content-between text-center">
					<ButtonDoubt
						parentCallback={this.handleButtonDoubtCallback}
						number={item.nomor}
						isDoubt={doubts[item.nomor] ? true : false}
					/>
					<div>
						<ButtonPrevious
							parentCallback={this.handleButtonPreviousCallback}
							number={this.props.previousItem !== undefined ? this.props.previousItem.nomor : 0}
						/>
						<ButtonNext
							parentCallback={this.handleButtonNextCallback}
							number={this.props.nextItem !== undefined ? this.props.nextItem.nomor : 0}
						/>
					</div>
					<ButtonSubmit
						parentCallback={this.handleButtonSubmitCallback}
						part={this.props.nextPart !== undefined ? this.props.nextPart.part : 0}
					/>
				</div>
			</div>
		);
	}
}

class Timer extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			part: 0,
			time: null
		}
	}

	componentDidUpdate = (props) => {
		// Compare props when first load
		if(this.props.part !== props.part || this.props.timeIsRunning !== props.timeIsRunning) {
			// Update state
			this.setState({
				part: this.props.part,
				time: this.props.time
			});

			// Start timer if the time is running
			clearInterval(this.timer);
			if(this.props.timeIsRunning) {
				window.setTimeout(() => {
					this.timer = window.setInterval(() => this.tick(), 1000);
				}, 500);
			}
		}
	}

	tick = () => {
		let {part, time} = this.state;

		if(time > 0) {
			// Decrement time
			this.setState({
				time: time - 1
			});
		}
		else {
			// Clear interval
			clearInterval(this.timer);

			// Move to next part
			if(this.props.nextPart !== 0) {
				alert("Akan berpindah ke bagian soal selanjutnya secara otomatis...");
				window.removeEventListener("beforeunload", j);
				this.props.parentCallback({
					part: this.props.nextPart
				});
			}
			else {
				alert("Tes akan dikumpulkan secara otomatis...");
				window.removeEventListener("beforeunload", j);
				window.location.href = '/dashboard';
			}
		}
	}

	timeToString = () => {
		let {time} = this.state;
		let h = Math.floor(time / 3600) < 10 ? '0' + Math.floor(time / 3600) : Math.floor(time / 3600);
		let m = Math.floor(time / 60) % 60 < 10 ? '0' + Math.floor(time / 60) % 60 : Math.floor(time / 60) % 60;
		let s = (time % 60) < 10 ? '0' + (time % 60) : (time % 60);
		return h + ' : ' + m + ' : ' + s;
	}

	render = () => {
		const {time} = this.state;

		if(time === null) {
			return <span><i class="fa fa-clock-o me-1"></i> Memulai...</span>
		}
		else if(time > 0) {
			return (
				<span class={time <= 10 ? 'text-danger' : ''}>
					<i class="fa fa-clock-o me-1"></i> {this.timeToString()}
				</span>
			)
		}
		else if(time == 0) {
			return <span class="text-danger"><i class="fa fa-clock-o me-1"></i> Waktu Habis!</span>
		}
	}
}

class ButtonDoubt extends React.Component {
	constructor(props) {
		super(props);
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
				class="btn btn-sm btn-warning m-1"
				onClick={() => this.handleClick(this.props.number)}
			>
				<i class={`fa ${this.props.isDoubt ? 'fa-thumbs-o-up' : 'fa-lightbulb-o'} me-1`}></i>
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
		if(this.props.number > 0){
			return (
				<button
					class="btn btn-sm btn-primary m-1"
					onClick={() => this.handleClick(this.props.number)}
				>
					<i class="fa fa-chevron-left me-1"></i> Sebelumnya
				</button>
			);
		}
		else return null;
	}
}

class ButtonNext extends React.Component {
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
		if(this.props.number > 0){
			return (
				<button
					class="btn btn-sm btn-primary m-1"
					onClick={() => this.handleClick(this.props.number)}
				>
					Selanjutnya <i class="fa fa-chevron-right ms-1"></i>
				</button>
			);
		}
		else return null;
	}
}

class ButtonSubmit extends React.Component {
	constructor(props) {
		super(props);
	}

	handleClick = () => {
		if(this.props.part !== 0) {
			let ask = confirm("Anda yakin ingin melanjutkan ke tes tahap berikutnya?");
			if(ask) {
				this.props.parentCallback({
					part: this.props.part
				});
			}
		}
		else {
			let ask = confirm("Anda yakin ingin mengumpulkan tes ini?");
			if(ask) {
				window.removeEventListener("beforeunload", j);
				window.location.href = '/dashboard';
			}
		}
	}

	render = () => {
		return (
			<button
				class="btn btn-sm btn-info m-1"
				onClick={this.handleClick}
			>
				<i class="fa fa-save me-1"></i> Submit
			</button>
		);
	}
}

class Choice extends React.Component {
	constructor(props) {
		super(props);
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

class TextField extends React.Component {
	constructor(props) {
		super(props);
	}

	handleChange = (event) => {
		// Callback to parent component
		this.props.parentCallback({
			number: this.props.number,
			answer: event.target.value,
		});
	}

	render = () => {
		return (
			<textarea
				class="form-control form-control-sm"
				rows="1"
				placeholder="Jawaban Anda..."
				value={this.props.value}
				onChange={this.handleChange}
			/>
		);
	}
}

class CheckboxNumber extends React.Component {
	constructor(props) {
		super(props);
	}

	handleChange = (event) => {
		// Callback to parent component
		this.props.parentCallback({
			number: this.props.number,
			value: event.target.value,
			isChecked: event.target.checked
		});
	}

	render = () => {
		let elements = [];
		for(var i = 0; i < 10; i++) {
			elements.push(
				<div class="form-check form-check-inline">
					<input
						class="form-check-input"
						type="checkbox"
						name={`checkbox[${this.props.number}]`}
						value={i}
						id={`checkbox-${i}`}
						checked={this.props.checkeds.indexOf(i.toString()) >= 0 ? true : false}
						onChange={this.handleChange}
					/>
					<label class="form-check-label" for={`checkbox-${i}`}>{i}</label>
				</div>
			);
		}
		return <React.Fragment>{elements}</React.Fragment>;
	}
}

class ImageChoice extends React.Component {
	constructor(props) {
		super(props);
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
			<div class="form-check form-check-inline radio-image">
				<input
					class="form-check-input d-none"
					type="radio"
					name={`choice[${this.props.number}]`}
					id={`choice-${this.props.option}`}
					value={this.props.option}
					checked={this.props.isChecked}
					onChange={() => this.handleChange(this.props.number, this.props.option)}
				/>
				<label class={`form-check-label border ${this.props.isChecked ? 'border-primary' : ''}`} for={`choice-${this.props.option}`}>
					<img width="100" src={`/assets/images/tes/ist/${this.props.description}`}/>
				</label>
			</div>
		);
	}
}

class ModalTutorial extends React.Component {
	constructor(props) {
		super(props);
	}

	componentDidUpdate = (props) => {
		// Compare props when first load
		if(this.props.part !== props.part) {
			// Show modal if not showed
			let modal = new bootstrap.Modal(document.getElementById("tutorialModal"));
			if(!document.body.classList.contains("modal-open")) modal.show();
		}
	}

	handleHide = () => {
		this.props.parentCallback({
			timeIsRunning: true
		})
	}

	render = () => {
		// HTML entity decode
		const HTMLEntityDecode = (escapedHTML: string) => React.createElement("div", { dangerouslySetInnerHTML: { __html: escapedHTML } });
		
		return (
			<div class="modal fade" id="tutorialModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Petunjuk Tes</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick={this.handleHide}></button>
						</div>
						<div class="modal-body">
							{HTMLEntityDecode(this.props.item.deskripsi_paket)}
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick={this.handleHide}><i class="fa fa-thumbs-o-up me-1"></i>Mengerti</button>
						</div>
					</div>
				</div>
			</div>
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
	.radio-image {margin-bottom: 1rem; padding-left: 0;}
	.radio-image label {cursor: pointer;}
	.radio-image label.border-primary {border-color: var(--color-1)!important; border-width: 2px!important;}
	/* #form {filter: blur(3px);} */

	.modal-auth .card-question, .modal-auth #nav-button {filter: blur(3px);}
	.modal-open .card-question .card-body {filter: blur(3px);}
	#question .btn:focus {box-shadow: none;}
	#nav-button {text-align: center;}
	#nav-button .btn {font-size: .75rem; width: 3.75rem; margin: .25rem;}
</style>
@endsection