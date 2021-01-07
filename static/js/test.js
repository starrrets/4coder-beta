const testForm = window['test-form'];
const testFormResults = window['test-result'];
const testFormTime = window['test-time'];
const timer = window.timer;
const timerMinutes = window.timer_minutes;
const timerSeconds = window.timer_seconds;
const testMinutes = timerMinutes.textContent;
const answerButton = window.answer;
const totalQuestions = window['progress-total'].textContent;
const progressCurrent = window['progress-current'];
const questionText = window['test-question__text'];
const questionAnsw = window['test-question__answers'];

const questions = JSON.parse(localStorage.getItem('testQuestions'));

if (!questions) {
	location.href = '/tests/';
} else {
	let currentID = localStorage.getItem('test-current');
	if (!currentID) {
		currentID = 0;
		localStorage.setItem('test-current', currentID);
	}

	let result = JSON.parse(localStorage.getItem('test-result'));
	if (!result) {
		result = [];
	}

	let testStart;
	let testEnd;
	let currentTest = location.href;
	if (
		currentTest == localStorage.getItem('current-test') &&
		localStorage.getItem('test-start')
	) {
		testStart = new Date(localStorage.getItem('test-start'));
		testEnd = new Date(localStorage.getItem('test-end'));
	} else {
		testStart = new Date();
		testEnd = new Date(testStart.getTime() + testMinutes * 60000);
		localStorage.setItem('current-test', currentTest);
		localStorage.setItem('test-start', testStart);
		localStorage.setItem('test-end', testEnd);
	}

	// функция вызываемая по завершению теста
	const getResult = () => {
		if (result.length != totalQuestions) {
			result = result.concat(new Array(totalQuestions - result.length));
		}
		testFormResults.value = JSON.stringify(result);
		let now = new Date();
		let testStart = new Date(localStorage.getItem('test-start'));
		let testEnd = new Date(localStorage.getItem('test-end'));
		let testTime;
		if (now > testEnd) {
			testTime = new Date(testEnd.getTime() - testStart.getTime());
		} else {
			testTime = new Date(now.getTime() - testStart.getTime());
		}
		let minutes =
			testTime.getMinutes() < 10
				? '0' + testTime.getMinutes()
				: testTime.getMinutes();
		let seconds =
			testTime.getSeconds() < 10
				? '0' + testTime.getSeconds()
				: testTime.getSeconds();

		testFormTime.value = minutes + ':' + seconds;
		localStorage.removeItem('current-test');
		localStorage.removeItem('test-start');
		localStorage.removeItem('test-end');
		localStorage.removeItem('test-current');
		localStorage.removeItem('test-result');
		localStorage.removeItem('testQuestions');
		testForm.submit();
	};

	const answersToHtml = (answType, answers) => {
		let result = '';
		switch (answType) {
			case 'radio':
				for (let i = 0; i < answers.length; i++) {
					result += `<input type="radio" name="test-question__answers" id="test-question__answers__item-${
						i + 1
					}" class="test-question__answers__item" value="${
						i + 1
					}"><label for="test-question__answers__item-${i + 1}">${
						answers[i]
					}</label>`;
				}
				break;
			case 'checkbox':
				for (let i = 0; i < answers.length; i++) {
					result += `<input type="checkbox" name="test-question__answers" id="test-question__answers__item-${
						i + 1
					}" class="test-question__answers__item" value="${
						i + 1
					}"><label for="test-question__answers__item-${i + 1}">${
						answers[i]
					}</label>`;
				}
				break;
			case 'text':
				result = `<input type="text" name="test-question__answers" id="test-question__answers__item" placeholder="Введите ответ">`;
				break;
			default:
				break;
		}
		return result;
	};

	let answerType;
	const setQuestion = (questionId) => {
		if (result.length < totalQuestions) {
			questionText.innerHTML = questions[questionId].question;
			questionAnsw.innerHTML = answersToHtml(
				questions[questionId].answerType,
				questions[questionId].answerVariants
			);
			progressCurrent.innerHTML = Number(questionId) + 1;
			answerButton.classList.add('disabled');
			if (questionAnsw.innerHTML.includes('radio')) {
				answerType = 'radio';
			} else if (questionAnsw.innerHTML.includes('checkbox')) {
				answerType = 'checkbox';
			} else {
				answerType = 'text';
			}
		} else getResult();
	};

	setQuestion(currentID);

	questionAnsw.onchange = () => {
		switch (answerType) {
			case 'radio':
				if (document.querySelector('input[type="radio"]:checked')) {
					answerButton.classList.remove('disabled');
				}
				break;
			case 'text':
				if (document.querySelector('input[type="text"]').value != '') {
					answerButton.classList.remove('disabled');
				} else {
					answerButton.classList.add('disabled');
				}
				break;
			case 'checkbox':
				if (document.querySelector('input[type="checkbox"]:checked')) {
					answerButton.classList.remove('disabled');
				} else {
					answerButton.classList.add('disabled');
				}
				break;
			default:
				break;
		}
	};

	// функция при наж кнопки, если id последний, то вызываем get result
	const answer = () => {
		let answ;
		switch (answerType) {
			case 'radio':
				answ = document.querySelector('input[name="test-question__answers"]:checked')
					.value;
				break;
			case 'text':
				answ = document.querySelector('input[type="text"]').value;
				break;
			case 'checkbox':
				let checkboxes = document.querySelectorAll(
					'input[name="test-question__answers"]:checked'
				);
				let tmp = [];
				checkboxes.forEach((checkbox) => {
					tmp.push(checkbox.value);
				});
				answ = tmp.toString();
				break;
			default:
				break;
		}
		result.push(answ);
		localStorage.setItem('test-result', JSON.stringify(result));
		currentID++;
		localStorage.setItem('test-current', currentID);
		setQuestion(currentID);
	};

	answerButton.onclick = () => {
		answer();
	};
	if (testEnd > new Date()) {
		((testEnd) => {
			let cd = setInterval(() => {
				let now = new Date().getTime();
				let diff = testEnd - now;
				let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
				let seconds = Math.floor((diff % (1000 * 60)) / 1000);
				minutes = minutes >= 10 ? minutes : '0' + minutes;
				seconds = seconds >= 10 ? seconds : '0' + seconds;
				timerMinutes.innerHTML = minutes;
				timerSeconds.innerHTML = seconds;
				if (diff < 0) {
					clearInterval(cd);
					timerMinutes.innerHTML = '00';
					timerSeconds.innerHTML = '00';
					getResult();
				}
			}, 1000);
		})(testEnd);
	} else {
		let dayDiff = new Date().getDay() - testEnd.getDay();
		if (dayDiff > 0 && result.length < totalQuestions) {
			localStorage.removeItem('current-test');
			localStorage.removeItem('test-start');
			localStorage.removeItem('test-end');
			localStorage.removeItem('test-current');
			localStorage.removeItem('test-result');
			location.href = '/tests/';
		} else {
			getResult();
		}
	}
}
