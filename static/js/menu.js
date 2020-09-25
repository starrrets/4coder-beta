const hamburger = document.querySelector('#header__hamburger');
const sidebar = document.querySelector('#sidebar');
const block = document.querySelector('#wrapper__block-elements');
const toggleElems = () => {
	sidebar.classList.toggle('open');
	block.classList.toggle('open');
};
hamburger.onclick = () => toggleElems();
block.onclick = () => toggleElems();
