const form = document.querySelector('form')
const submit = window.auth_button;

const pattern = /(?=^.{8,}$)(?=.*[а-яА-ЯёЁa-zA-Z])(?=.*[0-9])/g;
const validate = () => {
    let val = pattern.test(form.pass.value);
    if(!val){
        popupAlert('Ошибка! Пароль должен содержать не менее восьми знаков, включать буквы, цифры.')
        return val; 
    }
    
}