export default class App extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			user: document.getElementById('user_id').value,
			test: 'ist',
			part: 1,
			parts: [],
			items: [],
			examples: [],
			activeItem: '',
			activeNumber: 0,
			answers: [],
			doubts: [],
			timeIsRunning: false,
			needAuth: false,
			isAuth: false
		};
	}

	componentDidMount = () => {
		this.getRequest(this.state.test, this.state.part, this.state.user);
	}

	getRequest = (test, part) => {
		// Fetch data
		fetch('/api/question?test=' + test + '&part=' + part + '&user=' + this.state.user)
			.then(response => response.json())
			.then(data => {
					this.setState({
						parts: data.parts,
						items: data.questions,
						examples: data.examples,
						activeItem: data.questions.length > 0 ? data.questions[0] : {},
						activeNumber: data.questions.length > 0 ? data.questions[0].number : null,
						needAuth: data.questions.length > 0 && data.questions[0].is_auth === 1 ? true : false,
						isAuth: data.questions.length > 0 && data.questions[0].is_auth === 1 ? false : true
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

	handleModalAuthCallback = (data) => {
		const {isAuth} = this.state;

		if(isAuth === false) {
			this.setState({
				isAuth: data.isAuth
			});
		}
	}

	handleModalTutorialCallback = (data) => {
		const {timeIsRunning} = this.state;

		if(timeIsRunning === false) {
			this.setState({
				timeIsRunning: data.timeIsRunning
			});
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
				if(number === items[i].number) {
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
				if((activeNumber - 1) === items[i].number) {
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
				if((activeNumber + 1) === items[i].number) {
					item = items[i];
				}
			}
		}

		return item;
	}

	render = () => {
		const {test, items, activeItem, activeNumber, answers, doubts, examples, timeIsRunning, needAuth, isAuth} = this.state;

		return (
			<React.Fragment>
				<ModalAuth
					parentCallback={this.handleModalAuthCallback}
					test={test}
					part={activeItem.part}
					needAuth={needAuth}
				/>
				<ModalTutorial
					parentCallback={this.handleModalTutorialCallback}
					part={activeItem.part}
					item={activeItem}
					examples={examples}
					isAuth={isAuth}
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
		this.state = {
			status: null,
			message: null
		}
	}

	componentDidUpdate = (props) => {
		// Compare part props and check auth neccessary
		if(this.props.part !== props.part && this.props.needAuth) {
			// Update state
			this.setState({
				status: null,
				message: null
			})

			// Get elements
			this.myModal = document.getElementById("modalAuth");

			// Show modal
			this.modal = new bootstrap.Modal(this.myModal);
			this.modal.show();

			// Add event when modal is shown
			this.myModal.addEventListener('shown.bs.modal', () => {
				document.body.classList.add("modal-auth");
			});
		}
	}

	handleSubmit = (event) => {
		event.preventDefault();

		// Handle authenticate
		this.handleAuth({
			test: this.props.test,
			part: this.props.part,
			user: document.getElementById("user_id").value,
			token: document.getElementById("inputToken").value
		});
	}

	handleAuth = (params) => {
		fetch('/api/question/auth', {
				method: 'POST',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(params)
			})
			.then(response => response.json())
			.then(data => {
					this.setState(data);

					// If auth is success, hide modal with delay
					if(data.status === true) {
						window.setTimeout(() => {
							this.modal.hide();
						}, 1000);

						// Add event when modal is hidden
						this.myModal.addEventListener('hidden.bs.modal', () => {
							document.body.classList.remove("modal-auth");
							document.getElementById("inputToken").value = null;
						});

						// Parent callback
						this.props.parentCallback({
							isAuth: true
						});
					}
				}
			);
	}

	render = () => {
		const {status, message} = this.state;

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
									<label class="form-label">Masukkan token yang diberikan petugas untuk bisa mengakses tes:</label>
									<input type="text" id="inputToken" class={`form-control ${status !== null ? status === true ? 'border-success' : 'border-danger' : ''}`} placeholder="Token" required/>
									<div class={`small mt-1 ${status === true ? 'text-success' : 'text-danger'}`}>{message}</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-sm btn-info"><i class="fa fa-save me-1"></i>Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		);
	}
}

class ModalTutorial extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			isTrying: 0,
			isMemorizing: null,
			timeMemorizing: 0,
			answers: [],
			checkAnswers: [],
			keyAnswers: []
		}
	}

	componentDidUpdate = (props) => {
		// Compare props when first load
		if(this.props.part !== props.part || this.props.isAuth !== props.isAuth) {
			// Show modal if not showed
			this.modal = new bootstrap.Modal(document.getElementById("tutorialModal"));
			if(!document.body.classList.contains("modal-open")) this.modal.show();

			// Show modal if authenticated
			if(document.body.classList.contains("modal-auth")) {
				window.setTimeout(() => this.modal.show(), 1500);
			}
		}
	}

	handleHide = () => {
		this.setState({
			isTrying: 0,
			isMemorizing: this.props.item.type === 'choice-memo' ? 1 : null,
			answers: [],
			checkAnswers: [],
			keyAnswers: []
		});

		this.props.parentCallback({
			timeIsRunning: true
		});
	}

	handleTry = () => {
		this.setState({
			isTrying: 1,
			isMemorizing: this.props.item.type === 'choice-memo' ? 1 : null
		});
	}

	handleChoice = (number, answer) => {
		let {answers} = this.state;
		answers[number] = answer;
		this.setState({
			answers: answers,
			checkAnswers: [],
			keyAnswers: []
		});
	}

	handleTextField = (event) => {
		let {answers} = this.state;
		let number = event.target.dataset.number;
		let value = event.target.value;
		answers[number] = value;
		this.setState({
			answers: answers,
			checkAnswers: [],
			keyAnswers: []
		});
	}

	handleCheckbox = (event) => {
		let {answers} = this.state;
		let number = event.target.dataset.number;
		let answerTemp = answers[number];
		let isChecked = event.target.checked;
		let value = event.target.value;

		// Define answer to array
		if(answerTemp === undefined) {
			answerTemp = [];
		}

		// If checkbox is checked
		if(isChecked) {
			// If value is not in answer array
			if(answerTemp.indexOf(value) < 0) {
				answerTemp.push(value);
				answers[number] = answerTemp;
			}
		}
		// If checkbox is not checked
		else {
			// If value is in answer array
			if(answerTemp.indexOf(value) >= 0) {
				answerTemp.splice(answerTemp.indexOf(value), 1);
				answers[number] = answerTemp;
			}
		}

		this.setState({
			answers: answers,
			checkAnswers: [],
			keyAnswers: []
		});
	}

	handleImage = (number, answer) => {
		let {answers} = this.state;
		answers[number] = answer;
		this.setState({
			answers: answers,
			checkAnswers: [],
			keyAnswers: []
		});
	}

	handleMemorize = () => {
		this.setState({
			isMemorizing: 0,
			timeMemorizing: this.props.item.memorizing_time
		});

		let time = this.props.item.memorizing_time;
		this.timer = window.setInterval(() => {
			time--; // Decrement time
			if(time <= 0) {
				clearInterval(this.timer);
				this.setState({
					isMemorizing: 1
				});
			}
			else {
				this.setState({
					timeMemorizing: time
				});
			}
		}, 1000);
	}

	handleCheck = () => {		
		let {answers} = this.state;

		const params = {
			part: this.props.item.part,
			answers: answers
		};

		fetch('/api/question/example/submit', {
				method: 'POST',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(params)
			})
			.then(response => response.json())
			.then(data => {
					this.setState({
						checkAnswers: data.checkAnswers,
						keyAnswers: data.keyAnswers,
					});
				}
			);
	}

	renderExamples = () => {
		const {isTrying, answers, checkAnswers, keyAnswers} = this.state;
		const item = this.props.item;

		if(isTrying === 1) {
			if(item.type === 'choice' || item.type === 'choice-memo') {
				return (
					<React.Fragment>
						<hr/>
						{
							this.props.examples.map((example, index) => {
								const choices = Object.entries(example.description[0].pilihan);
								return (
									<div class="mb-3">
										<p class="mb-1"><strong>Contoh Soal {example.number}:</strong></p>
										<p class="mb-1">{example.description !== undefined ? example.description[0].soal : ''}</p>
										{
											choices.map((choice) => {
												return (
													<div class="form-check">
														<input
															class="form-check-input"
															type="radio"
															name={`choicex[${example.number}]`}
															id={`choice-${example.number}-${choice[0]}`}
															value={choice[0]}
															onChange={() => this.handleChoice(example.number, choice[0])}
														/>
														<label class="form-check-label" for={`choice-${example.number}-${choice[0]}`}>{choice[1]}</label>
													</div>
												)
											})
										}
										<div class={`alert ${checkAnswers[example.number] ? 'alert-success' : 'alert-danger'} ${checkAnswers[example.number] !== undefined ? '' : 'd-none'}`}>
											<strong>Jawaban Anda {checkAnswers[example.number] ? 'benar' : 'salah'}!</strong>&nbsp;
											<span class={checkAnswers[example.number] === false ? '' : 'd-none'}>Jawaban yang tepat adalah <strong>{keyAnswers[example.number]}</strong>.</span>
											<br/>
											<u>Pembahasan:</u> {example.description[0].pembahasan !== undefined ? example.description[0].pembahasan : '-'}
										</div>
									</div>
								)
							})
						}
					</React.Fragment>
				);
			}
			else if(item.type === 'essay') {
				return (
					<React.Fragment>
						<hr/>
						{
							this.props.examples.map((example, index) => {
								return (
									<div class="mb-3">
										<p class="mb-1"><strong>Contoh Soal {example.number}:</strong></p>
										<p class="mb-1">{example.description !== undefined ? example.description[0].soal : ''}</p>
										<textarea
											class="form-control form-control-sm mb-2"
											rows="1"
											placeholder="Jawaban Anda..."
											data-number={example.number}
											onChange={this.handleTextField}
										/>
										<div class={`alert ${checkAnswers[example.number] ? 'alert-success' : 'alert-danger'} ${checkAnswers[example.number] !== undefined ? '' : 'd-none'}`}>
											<strong>Jawaban Anda {checkAnswers[example.number] ? 'benar' : 'salah'}!</strong>&nbsp;
											<span class={checkAnswers[example.number] === false ? '' : 'd-none'}>Jawaban yang tepat adalah <strong>{keyAnswers[example.number]}</strong>.</span>
											<br/>
											<u>Pembahasan:</u> {example.description[0].pembahasan !== undefined ? example.description[0].pembahasan : '-'}
										</div>
									</div>
								)
							})
						}
					</React.Fragment>
				);
			}
			else if(item.type === 'number') {
				return (
					<React.Fragment>
						<hr/>
						{
							this.props.examples.map((example, index) => {											
								let elements = [];
								for(var i = 0; i < 10; i++) {
									elements.push(
										<div class="form-check form-check-inline">
											<input
												class="form-check-input"
												type="checkbox"
												name={`checkboxx[${example.number}]`}
												value={i}
												id={`checkbox-${example.number}-${i}`}
												data-number={example.number}
												onChange={this.handleCheckbox}
											/>
											<label class="form-check-label" for={`checkbox-${example.number}-${i}`}>{i}</label>
										</div>
									);
								}

								return (
									<div class="mb-3">
										<p class="mb-1"><strong>Contoh Soal {example.number}:</strong></p>
										<p class="mb-1">{example.description !== undefined ? example.description[0].soal : ''}</p>
										{elements}
										<div class={`alert ${checkAnswers[example.number] ? 'alert-success' : 'alert-danger'} ${checkAnswers[example.number] !== undefined ? '' : 'd-none'}`}>
											<strong>Jawaban Anda {checkAnswers[example.number] ? 'benar' : 'salah'}!</strong>&nbsp;
											<span class={checkAnswers[example.number] === false ? '' : 'd-none'}>Jawaban yang tepat adalah <strong>{keyAnswers[example.number]}</strong>.</span>
											<br/>
											<u>Pembahasan:</u> {example.description[0].pembahasan !== undefined ? example.description[0].pembahasan : '-'}
										</div>
									</div>
								)
							})
						}
					</React.Fragment>
				);
			}
			else if(item.type === 'image') {
				const {answers} = this.state;

				return (
					<React.Fragment>
						<hr/>
						{
							this.props.examples.map((example, index) => {
								const choices = Object.entries(example.description[0].pilihan);
								return (
									<div class="mb-3">
										<p class="mb-1"><strong>Contoh Soal {example.number}:</strong></p>
										<p class="mb-1"><img width="125" src={`/assets/images/tes/ist/${example.description !== undefined ? example.description[0].soal : ''}`}/></p>
										<p class="mb-1">Pilih Jawaban:</p>
										{
											choices.map((choice) => {
												return (													
													<div class="form-check form-check-inline radio-image">
														<input
															class="form-check-input d-none"
															type="radio"
															name={`choice[${example.number}]`}
															id={`choice-${example.number}-${choice[0]}`}
															value={choice[0]}
															onChange={() => this.handleImage(example.number, choice[0])}
														/>
														<label class={`form-check-label border ${answers[example.number] === choice[0] ? 'border-primary' : ''}`} for={`choice-${example.number}-${choice[0]}`}>
															<img width="100" src={`/assets/images/tes/ist/${choice[1]}`}/>
														</label>
													</div>
												)
											})
										}
										<div class={`alert ${checkAnswers[example.number] ? 'alert-success' : 'alert-danger'} ${checkAnswers[example.number] !== undefined ? '' : 'd-none'}`}>
											<strong>Jawaban Anda {checkAnswers[example.number] ? 'benar' : 'salah'}!</strong>&nbsp;
											<span class={checkAnswers[example.number] === false ? '' : 'd-none'}>Jawaban yang tepat adalah <strong>{keyAnswers[example.number]}</strong>.</span>
											<br/>
											<u>Pembahasan:</u> {example.description[0].pembahasan !== undefined ? example.description[0].pembahasan : '-'}
										</div>
									</div>
								)
							})
						}
					</React.Fragment>
				);
			}
		}
		else return null;
	}

	render = () => {
		// HTML entity decode
		const HTMLEntityDecode = (escapedHTML) => React.createElement("div", { dangerouslySetInnerHTML: { __html: escapedHTML } });
		
		const {isTrying, isMemorizing, timeMemorizing} = this.state;
		
		return (
			<div class="modal fade" id="tutorialModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Petunjuk Tes</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick={this.handleHide}></button>
						</div>
						<div class="modal-body">
							{HTMLEntityDecode(this.props.item.tutorial)}
							<div class="mt-2">
								<button type="button" class={`btn btn-sm btn-info ${this.props.item.type === 'choice-memo' && isMemorizing !== 1 ? '' : 'd-none'}`} onClick={this.handleMemorize}>
									<i class="fa fa-clipboard me-1"></i>Membaca Hafalan
								</button>
							</div>
							<div class={`to-memorize ${isMemorizing === 0 ? '' : 'd-none'}`}>
								<p class="my-1">Waktu menghafal tersisa: <strong>{timeMemorizing} detik.</strong></p>
								<img src="/assets/images/tes/ist/Hafalan.png" class="img-fluid img-thumbnail mt-2"/>
							</div>
							<div class="examples">{this.renderExamples()}</div>
						</div>
						<div class={`modal-footer ${this.props.examples.length === 0 ? '' : 'justify-content-between'}`}>
							<button type="button" class={`btn btn-sm btn-warning ${this.props.examples.length === 0 || isTrying === 1 || (this.props.item.type === 'choice-memo' && isMemorizing === null) ? 'd-none' : ''}`} onClick={this.handleTry}>
								<i class="fa fa-pencil me-1"></i>Latihan Soal
							</button>
							<button type="button" class={`btn btn-sm btn-info ${isTrying === 1 ? '' : 'd-none'}`} onClick={this.handleCheck}>
								<i class="fa fa-save me-1"></i>Cek Jawaban
							</button>
							<button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal" onClick={this.handleHide}>
								<i class="fa fa-thumbs-o-up me-1"></i>Mengerti
							</button>
						</div>
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
							if(doubts[item.number] === true) buttonColor = 'btn-warning';
							else if(answers[item.number] !== undefined && (((item.type === 'choice' || item.type === 'image' || item.type === 'choice-memo') && answers[item.number] !== null) || (item.type === 'essay' && answers[item.number] !== '') || (item.type === 'number' && answers[item.number].length > 0))) buttonColor = 'btn-primary';
							else if(item.number === activeNumber && (answers[item.number] === undefined || answers[item.number] === null || answers[item.number] === '' || answers[item.number].length === 0)) buttonColor = 'btn-info';
							else buttonColor = 'btn-outline-dark';

							// Set button note
							let buttonNote = '-';
							if(answers[item.number] !== undefined) {
								if((item.type === 'choice' || item.type === 'choice-memo') && answers[item.number] !== null) buttonNote = answers[item.number];
								else if(item.type === 'essay' && answers[item.number] !== '') buttonNote = 'Y';
								else if(item.type === 'number' && answers[item.number].length > 0) buttonNote = 'Y';
								else if(item.type === 'image' && answers[item.number] !== null) buttonNote = answers[item.number];
							}

							return (
								<button
									class={`btn btn-sm ${buttonColor}`}
									onClick={() => this.handleClick(item.number)}
								>
									{item.number} ({buttonNote})
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
		const question = this.props.item.description;
		const choices = (question !== undefined && question[0].pilihan !== undefined) ? Object.entries(question[0].pilihan) : [];

		if(item.type === 'choice' || item.type === 'choice-memo') {
			return (
				<React.Fragment>
					<p>{item.description !== undefined ? item.description[0].soal : ''}</p>
					{
						choices.map((choice) => {
							return (
								<Choice
									parentCallback={this.handleChoiceCallback}
									number={item.number}
									option={choice[0]}
									description={choice[1]}
									isChecked={answers[item.number] === choice[0] ? true : false}
								/>
							)
						})
					}
				</React.Fragment>
			);
		}
		else if(item.type === 'essay') {
			return (
				<React.Fragment>
					<p>{item.description !== undefined ? item.description[0].soal : ''}</p>
					<TextField
						parentCallback={this.handleChoiceCallback}
						number={item.number}
						value={answers[item.number] !== undefined ? answers[item.number] : ''}
					/>
				</React.Fragment>
			);
		}
		else if(item.type === 'number') {
			return (
				<React.Fragment>
					<p>{item.description !== undefined ? item.description[0].soal : ''}</p>
					<CheckboxNumber
						parentCallback={this.handleCheckboxNumberCallback}
						number={item.number}
						checkeds={answers[item.number] !== undefined ? answers[item.number] : []}
					/>
				</React.Fragment>
			);
		}
		else if(item.type === 'image') {
			return (
				<React.Fragment>
					<p><img width="125" src={`/assets/images/tes/ist/${item.description !== undefined ? item.description[0].soal : ''}`}/></p>
					<p>Pilih Jawaban:</p>
					{
						choices.map((choice) => {
							return (
								<ImageChoice
									parentCallback={this.handleChoiceCallback}
									number={item.number}
									option={choice[0]}
									description={choice[1]}
									isChecked={answers[item.number] === choice[0] ? true : false}
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
					<span class="fw-bold"><i class="fa fa-edit"></i> Soal {item.number}</span>
					<span>
						<Timer
							parentCallback={this.handleTimerCallback}
							part={item.part}
							time={item.exam_time}
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
						number={item.number}
						isDoubt={doubts[item.number] ? true : false}
					/>
					<div>
						<ButtonPrevious
							parentCallback={this.handleButtonPreviousCallback}
							number={this.props.previousItem !== undefined ? this.props.previousItem.number : 0}
						/>
						<ButtonNext
							parentCallback={this.handleButtonNextCallback}
							number={this.props.nextItem !== undefined ? this.props.nextItem.number : 0}
						/>
					</div>
					<ButtonSubmit
						parentCallback={this.handleButtonSubmitCallback}
						test={item.path}
						part={this.props.nextPart !== undefined ? this.props.nextPart.part : 0}
						answers={answers}
						doubts={doubts}
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
				this.props.parentCallback({
					part: this.props.nextPart
				});
			}
			else {
				// alert("Tes akan dikumpulkan secara otomatis...");
				// window.removeEventListener("beforeunload", j);
				// window.location.href = '/dashboard';
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
		// If it is moving to the next part
		if(this.props.part !== 0) {
			let ask = confirm("Anda yakin ingin melanjutkan ke tes tahap berikutnya?");
			if(ask) {
				this.props.parentCallback({
					part: this.props.part
				});
			}
		}
		// If it is submitting
		else {
			let ask = confirm("Anda yakin ingin mengumpulkan tes ini?");
			if(ask) {
				this.handleSubmit();
				alert("Tes Anda sudah dikumpulkan.");
				window.removeEventListener("beforeunload", j);
				window.location.href = '/dashboard';
			}
		}
	}

	handleSubmit = () => {
		const params = {
			answers: this.props.answers,
			doubts: this.props.doubts,
			user_id: document.getElementById("user_id").value
		};

		fetch('/api/question/submit', {
				method: 'POST',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(params)
			})
			.then(response => response.json())
			.then(data => {
					console.log(data);
				}
			);
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