const hamburger = document.querySelector('#header__hamburger');
const sidebarHamburger =   document.querySelector('#sidebar__hamburger');
const sidebar = document.querySelector('#sidebar');
const block = document.querySelector('#wrapper__block-elements');
let header = document.querySelector('header');
let content = window.main;
const toggleElems = () => {
	sidebar.classList.toggle('open');
	block.classList.toggle('open');
	body.style = body.style.overflowY == '' ? 'overflow-y:hidden;' : '';
	content.style.filter = content.style.filter==''? 'blur(12px)' :'';
};
hamburger.onclick = () => toggleElems();
sidebarHamburger.onclick = () => toggleElems();
block.onclick = () => toggleElems();
