const showAuth = () => {
	window['registration'].style = 'opacity:1;visibility:visible;';
	window['page__block'].style = 'opacity:1;visibility:visible;z-index:9';
};

const closeAuth = () => {
	window['registration'].style = 'opacity:0;visibility:hidden;';
	window['page__block'].style = 'opacity:0;visibility:hidden;';
};

if (window['header__button']) {
	window['header__button'].onclick = () => showAuth();
	window['page__block'].onclick = () => closeAuth();
}
