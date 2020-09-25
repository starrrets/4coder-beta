const hamburger = window.header__hamburger;
const sidebar = window.sidebar;
hamburger.onclick = () => {
	sidebar.classList.toggle('open');
};
