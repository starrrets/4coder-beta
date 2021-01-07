const changeForm = window['user-edit'];
const avatarForm = window['avatar-edit'];
const passwordForm = window['password-change'];
const passwordAddForm = window['password-add'];

const changeButton = window['user-info__change_button'];
const changeAvatarButton = window['user-edit__avatar-edit__change'];
const changePasswordButton = window['password_change'];
const addPasswordButton = window['password_add'];

const deleteAvatar = window['user-edit__avatar-edit__delete'];
const cancelChange = window['cancel'];
const cancelAvatarChange = window['avatar-cancel'];
const cancelPasswordChange = window['password-cancel'];
const cancelPasswordAdd = window['password-add-cancel'];

const oldAvatar = window['user-info__avatar'].style.backgroundImage.slice(5, -2);
const oldUsername = window['user-info__username'].textContent;

const showForm = (form) => {
	form.style.opacity = '1';
	form.style.visibility = 'visible';
};

const hideForm = (form) => {
	form.style.opacity = '0';
	form.style.visibility = 'hidden';
};
const showPageBlock = () => {
	pageBlock.style.opacity = '1';
	pageBlock.style.visibility = 'visible';
};

const hidePageBlock = () => {
	pageBlock.style.opacity = '0';
	pageBlock.style.visibility = 'hidden';
};
changeButton.onclick = () => {
	showPageBlock();
	showForm(changeForm);
};

cancelChange.onclick = () => {
	hidePageBlock();
	hideForm(changeForm);
	window['username'].value = oldUsername;
	window['current-avatar'].style = 'background-image:url("' + oldAvatar + '")';
	window['avatar'].value = oldAvatar;
	return false;
};

changeAvatarButton.onclick = () => {
	hideForm(changeForm);
	showForm(avatarForm);
	return false;
};
if (changePasswordButton) {
	changePasswordButton.onclick = () => {
		hideForm(changeForm);
		showForm(passwordForm);
		return false;
	};

	cancelPasswordChange.onclick = () => {
		hideForm(passwordForm);
		showForm(changeForm);
		window['old-password'].value = '';
		window['new-password'].value = '';
		return false;
	};
}

if (addPasswordButton) {
	addPasswordButton.onclick = () => {
		hideForm(changeForm);
		showForm(passwordAddForm);
		return false;
	};

	cancelPasswordAdd.onclick = () => {
		hideForm(passwordAddForm);
		showForm(changeForm);
		window['add-password'].value = '';
		return false;
	};
}

deleteAvatar.onclick = () => {
	let defaultAvatar = '../static/img/user.webp';
	window['current-avatar'].style = 'background-image:url("../static/img/user.webp")';
	window['avatar'].value = defaultAvatar;
	return false;
};

cancelAvatarChange.onclick = () => {
	hideForm(avatarForm);
	showForm(changeForm);
	window['new-avatar'].value = '';
	return false;
};

window['avatar-edit__submit'].onclick = () => {
	let url = /https?:\/\//g;
	let newAvatar = window['new-avatar'].value;
	if (url.test(newAvatar)) {
		hideForm(avatarForm);
		showForm(changeForm);
		window['new-avatar'].value = '';
		window['current-avatar'].style = 'background-image:url("' + newAvatar + '")';
		window['avatar'].value = newAvatar;
	} else {
		popupAlertF('Проверьте вашу ссылку на изображение!');
	}
	return false;
};
