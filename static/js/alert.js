const alert = document.querySelector('.alert');
const alertBtn = window.alert__btn;
const alertTitle = window.alert__title;
const pageBlock = window.page__block;

const closeAlert = ()=>{
    alertTitle.textContent = '';
    alert.style = 'opacity:0;visibility:hidden;';
    pageBlock.style = 'opacity:0;visibility:hidden;';
}

const popupAlert = (text) => {
    alertTitle.textContent = text;
    alert.style = 'opacity:1;visibility:visible;';
    pageBlock.style = 'opacity:1;visibility:visible;';
}

alertBtn.onclick = () => closeAlert();
pageBlock.onclick = () => closeAlert();