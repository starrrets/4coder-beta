const alert = document.querySelector('.alert');
const alertBtn = window.alert__btn;
const alertTitle = window.alert__title;
const pageBlock = window.page__block;
// let body = document.querySelector('body');
let a = 10;
const closeAlert = () => {
	alertTitle.textContent = '';
	alert.style = 'opacity:0;visibility:hidden;';
	pageBlock.style = 'opacity:0;visibility:hidden;';
	body.style = '';
};
const closeAlertF = () => {
	alertTitle.textContent = '';
	alert.style = 'opacity:0;visibility:hidden;';
	pageBlock.style = 'opacity:1;visibility:visible;z-index:10';
	body.style = '';
};
const popupAlert = (text) => {
	alertTitle.textContent = text;
	alert.style = 'opacity:1;visibility:visible;';
	pageBlock.style = 'opacity:1;visibility:visible;';
	alertBtn.onclick = () => closeAlert();
	body.style = 'overflow-y:hidden';
};

const popupAlertF = (text) => {
	alertTitle.textContent = text;
	alert.style = 'opacity:1;visibility:visible;';
	pageBlock.style = 'opacity:1;visibility:visible;z-index:9';
	alertBtn.onclick = () => closeAlertF();
	body.style = 'overflow-y:hidden';
};

if (window['user-edit']) {
	pageBlock.onclick = () => false;
} else {
	pageBlock.onclick = () => closeAlert();
}
