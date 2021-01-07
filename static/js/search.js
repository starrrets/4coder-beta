const buttonAll = window['button-all'];
const buttonEducation = window['button-education'];
const buttonTasks = window['button-tasks'];
const buttonTests = window['button-tests'];
const results = document.querySelectorAll('.search-result__link');

const filter = (linkClass) => {
	switch (linkClass) {
		case 'all':
			for (let i = 0; i < results.length; i++) {
				results[i].style.display = 'block';
			}
			break;
		default:
			for (let i = 0; i < results.length; i++) {
				if (!results[i].classList.contains(linkClass)) {
					results[i].style.display = 'none';
				} else results[i].style.display = 'block';
			}
			break;
	}
};

const changeCurrentButton = (newCurrent) => {
	let old = document.querySelector('.current-button');
	old.classList.remove('current-button');
	window['button-' + newCurrent].classList.add('current-button');
	sessionStorage.setItem('current-search-button', newCurrent);
};

let currentSearchButton = sessionStorage.getItem('current-search-button');
if (!currentSearchButton) {
	currentSearchButton = 'all';
	sessionStorage.setItem('current-search-button', currentSearchButton);
} else {
	changeCurrentButton(currentSearchButton);
	filter(currentSearchButton);
}

buttonAll.onclick = () => {
	changeCurrentButton('all');
	filter('all');
};
buttonEducation.onclick = () => {
	changeCurrentButton('education');
	filter('education');
};
buttonTasks.onclick = () => {
	changeCurrentButton('tasks');
	filter('tasks');
};
buttonTests.onclick = () => {
	changeCurrentButton('tests');
	filter('tests');
};
