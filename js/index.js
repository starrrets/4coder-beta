const toggle = window.toggle;
const wrapper = window.wrapper;
const toggleTheme = (theme) => {
	theme = theme === 'dark' ? 'light' : 'dark';
	wrapper.className = theme;
	toggle.className = theme;
};

const setTheme = (theme) => {
	wrapper.className = theme;
	toggle.className = theme;
};

window.onload = function () {
	let theme = localStorage.getItem('theme');
	setTheme(theme);
};

window.onbeforeunload = function () {
	localStorage.setItem('theme', toggle.className);
};

toggle.onclick = function () {
	let theme = toggle.className;
	toggleTheme(theme);
};
