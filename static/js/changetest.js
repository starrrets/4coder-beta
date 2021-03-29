const addQuestion = window['questions-add'];
const questionsBox = document.querySelector('.questions');
let test = JSON.parse(window['test'].value);
let answers = JSON.parse(window['answers'].value);
let questionsCounter = 0;

function htmlToElement(html) {
	var template = document.createElement('template');
	html = html.trim();
	template.innerHTML = html;
	return template.content.firstChild;
}

(function (test, answers) {
	for (let i = 0; i < test.length; i++) {
		++questionsCounter;
		let answ = '';
		switch (test[i].answerType) {
			case 'text':
				answ = `<input type='text' class='question-answers__text' placeholder='Введите правильный ответ (или перечилите через ";")' value='${answers[i]}'>`;
				break;

			default:
				let testVariants = test[i].answerVariants;
				for (let j = 0; j < testVariants.length; j++) {
					let answId = j + 1;
					answ += `<input type='${
						test[i].answerType === 'radio' ? 'radio' : 'checkbox'
					}' name='input-${questionsCounter}' value='${answId}' ${
						answers[i].toString().includes(answId) ? 'checked' : ''
					}><input type='text' class='answer-text' placeholder='Введите вариант ответа' value='${
						testVariants[j]
					}'>`;
				}
				break;
		}
		let questionTemplate = `<div class='question' id='question-${questionsCounter}'><h3 class='question-text__title'>Вопрос-${questionsCounter}</h3><button type='button' class='question-delete'></button><textarea name='questionText_${questionsCounter}'  id='questionText_${questionsCounter}'>${
			test[i].question
		}</textarea><div class='question-answer__controls'><h3 class='question-answers__title'>Ответы-${questionsCounter}</h3><select class='answer-type'><option ${
			test[i].answerType === 'text' ? 'selected' : ''
		}>text</option><option ${
			test[i].answerType === 'radio' ? 'selected' : ''
		}>radio</option><option ${
			test[i].answerType === 'checkbox' ? 'selected' : ''
		}>checkbox</option></select>${
			test[i].answerType !== 'text'
				? `<button type='button' class='question-answer__button question-answer__add'>+</button><button type='button' class='question-answer__button question-answer__delete'>-</button>`
				: ''
		}</div><div class='question-answers'>${answ}</div></div>`;
		questionsBox.insertBefore(htmlToElement(questionTemplate), addQuestion);
		CKEDITOR.replace(`questionText_${questionsCounter}`, {
			customConfig: './config-min.js',
		});
	}
})(test, answers);

addQuestion.onclick = () => {
	++questionsCounter;
	let questionTemplate = `<div class='question' id='question-${questionsCounter}'><h3 class='question-text__title'>Вопрос-${questionsCounter}</h3><button type='button' class='question-delete'></button><textarea name='questionText_${questionsCounter}'  id='questionText_${questionsCounter}'></textarea><div class='question-answer__controls'><h3 class='question-answers__title'>Ответы-${questionsCounter}</h3><select class='answer-type'><option>text</option><option>radio</option><option>checkbox</option></select></div><div class='question-answers'><input type='text' class='question-answers__text' placeholder='Введите правильный ответ (или перечилите через ";")'></div></div>`;
	questionsBox.insertBefore(htmlToElement(questionTemplate), addQuestion);
	CKEDITOR.replace(`questionText_${questionsCounter}`, {
		customConfig: './config-min.js',
	});
};
window.onclick = e => {
	let clicked = e.target;
	if (clicked.className === 'question-delete') {
		clicked.parentElement.outerHTML = '';
		let id = clicked.parentElement.id.slice(9);
		delete CKEDITOR.instances['questionText_' + id];
		--questionsCounter;
		let questions = document.querySelectorAll('.question');
		for (let i = 0; i < questions.length; i++) {
			questions[i].id = `question-${i + 1}`;
			questions[i].querySelector(
				'.question-text__title'
			).textContent = `Вопрос-${i + 1}`;
			questions[i].querySelector(
				'.question-answers__title'
			).textContent = `Ответы-${i + 1}`;
			let textarea = questions[i].querySelector('textarea');
			let editorBlock = textarea.nextSibling;
			let editorInstances = CKEDITOR.instances;
			let editor = editorInstances[textarea.id];
			let data = editor.getData();
			delete CKEDITOR.instances[textarea.id];
			editorBlock.outerHTML = '';
			textarea.id = `questionText_${i + 1}`;
			textarea.name = `questionText_${i + 1}`;
			textarea.innerHTML = data;
			CKEDITOR.replace(`questionText_${i + 1}`, {
				customConfig: './config-min.js',
			});
		}
	} else if (clicked.classList.contains('question-answer__add')) {
		let answersBox = clicked.parentElement.nextSibling;
		let inputType = clicked.previousSibling.value;
		let inputs = answersBox.querySelectorAll(`input[type='${inputType}']`);
		let inputName = inputs[0].name;
		let lastValue = inputs[inputs.length - 1].value;
		answersBox.appendChild(
			htmlToElement(
				`<input type='${inputType}' name='${inputName}' value='${++lastValue}'>`
			)
		);
		answersBox.appendChild(
			htmlToElement(
				`<input type='text' class='answer-text' placeholder='Введите вариант ответа'>`
			)
		);
		console.log(answersBox);
	} else if (clicked.classList.contains('question-answer__delete')) {
		let answersBox = clicked.parentElement.nextSibling;
		if (answersBox.childElementCount > 2) {
			answersBox.removeChild(answersBox.lastChild);
			answersBox.removeChild(answersBox.lastChild);
		}
	}
};

questionsBox.onchange = e => {
	let target = e.target;
	if (target.className === 'answer-type') {
		let answersBox = target.parentElement.nextSibling;
		let questionId = target.previousSibling.textContent.slice(7);
		if (target.value !== 'text') {
			if (!target.parentElement.querySelector('.question-answer__button')) {
				target.parentElement.appendChild(
					htmlToElement(
						`<button type='button' class='question-answer__button question-answer__add'>+</button>`
					)
				);
				target.parentElement.appendChild(
					htmlToElement(
						`<button type='button' class='question-answer__button question-answer__delete'>-</button>`
					)
				);
			}
			if (!answersBox.querySelector('.answer-text')) {
				answersBox.innerHTML = '';
				answersBox.appendChild(
					htmlToElement(
						`<input type='${
							target.value === 'radio' ? 'radio' : 'checkbox'
						}' name='input-${questionId}' value='1'>`
					)
				);
				answersBox.appendChild(
					htmlToElement(
						`<input type='text' class='answer-text' placeholder='Введите вариант ответа'>`
					)
				);
			} else {
				let radCheck = answersBox.querySelectorAll(
					`input[type='${target.value === 'radio' ? 'checkbox' : 'radio'}']`
				);
				radCheck.forEach(element => {
					element.type = target.value === 'radio' ? 'radio' : 'checkbox';
				});
			}
		} else {
			let qButtons = target.parentElement.querySelectorAll(
				'.question-answer__button'
			);
			target.parentElement.removeChild(qButtons[0]);
			target.parentElement.removeChild(qButtons[1]);
			answersBox.innerHTML = '';
			answersBox.appendChild(
				htmlToElement(
					`<input type='text' class='question-answers__text' placeholder='Введите правильный ответ (или перечилите через ";")'>`
				)
			);
		}
	}
};

window.onsubmit = () => {
	let questions = questionsBox.querySelectorAll('.question');
	let updatedTest = [];
	let updatedAnswers = [];
	for (let i = 0; i < questions.length; i++) {
		const element = questions[i];

		let question = CKEDITOR.instances[`questionText_${i + 1}`]
			.getData()
			.replace(/(\r\n|\n|\r)/gm, '');

		let answerType = element.querySelector('.answer-type').value;

		let answerVariants = [];
		let variants = element.querySelectorAll('input[type="text"]');
		if (answerType !== 'text') {
			variants.forEach(element => {
				answerVariants.push(element.value);
			});
		}

		updatedTest.push({ question, answerType, answerVariants });

		let answer;
		switch (answerType) {
			case 'text':
				answer = element.querySelector('input[type="text"]').value;
				break;
			case 'radio':
				answer = element.querySelector('input[type="radio"]:checked').value;
				break;
			case 'checkbox':
				let checkboxes = element.querySelectorAll(
					'input[type="checkbox"]:checked'
				);
				let tmp = [];
				checkboxes.forEach(checkbox => {
					tmp.push(checkbox.value);
				});
				answer = tmp.toString();
				break;
			default:
				break;
		}

		updatedAnswers.push(answer);
	}
	document.querySelector(`#test`).innerHTML = JSON.stringify(updatedTest);
	document.querySelector(`#answers`).innerHTML = JSON.stringify(updatedAnswers);
	return true;
};
