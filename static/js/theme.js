const toggle = window.toggle;
const wrapper = window.wrapper;
let body = document.querySelector('body');
const toggleTheme = theme => {
	theme = theme === 'dark' ? 'light' : 'dark';
	wrapper.className = theme;
	toggle.className = theme;
};

const setTheme = theme => {
	wrapper.className = theme;
	toggle.className = theme;
};

window.onload = function () {
	let theme = localStorage.getItem('theme') ?? 'light';
	setTheme(theme);
	if (location.href.includes('list.php')) {
		let curList = sessionStorage.getItem('current-list');
		if (curList) {
			changeCurrent(curList);
		}
	}
	if (
		!location.href.includes('test.php') &&
		localStorage.getItem('current-test')
	) {
		if (location.href.includes('tests'))
			location.href = localStorage.getItem('current-test');
	}
};

window.onbeforeunload = function () {
	localStorage.setItem('theme', toggle.className);
	if (
		location.href.includes('list.php') &&
		!location.href.includes('education')
	) {
		sessionStorage.setItem(
			'current-list',
			document.querySelector('.list__difficulty__button--current').id
		);
	}
};

toggle.onclick = function () {
	let theme = toggle.className;
	toggleTheme(theme);
};
