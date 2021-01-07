const changeCurrent = (diff) => {
    let current = document.querySelector('.list__difficulty__button--current');
    let currentList = document.querySelector('.list__section--current');
    current.classList.toggle('list__difficulty__button--current');
    currentList.classList.toggle('list__section--current');
    console.log(diff)
    window[diff].classList.toggle('list__difficulty__button--current'); 
    window['list__section__'+diff].classList.toggle('list__section--current');   
}

const diffButtons = document.getElementsByClassName('list__difficulty__button');
for(let i = 0; i < 3; i++){
    diffButtons[i].onclick = () => {changeCurrent(diffButtons[i].id)}
}