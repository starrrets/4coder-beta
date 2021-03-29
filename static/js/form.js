const showAuth = () => {
	window['registration'].style = 'opacity:1;visibility:visible;';
	window['page__block'].style = 'opacity:1;visibility:visible;z-index:9';
	if(window.innerWidth>1280 || (window.innerWidth - document.body.clientWidth)>0){
		header.style = 'padding-right:27px';
		window.wrapper.style = 'padding-right:15px';
	}
	body.style = 'overflow-y:hidden;';
	content.style.filter = 'blur(30px)';

};

const closeAuth = () => {
	window['registration'].style = 'opacity:0;visibility:hidden;';
	window['page__block'].style = 'opacity:0;visibility:hidden;';
	body.style = '';
	if ((window.innerWidth - document.body.clientWidth)==0){
		window.wrapper.style = '';
		header.style = '';
	}
	content.style.filter =  '' ;

};

if (window['header__button']) {
	window['header__button'].onclick = () => showAuth();
	window['page__block'].onclick = () => closeAuth();
}
if(window['cta-container__button']){
	window['cta-container__button'].onclick = () => showAuth();
}