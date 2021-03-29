window.onchange = e => {
    let link = e.target.nextSibling;
    let newRole = e.target.value;
    if(!link.classList.contains(newRole)){
        link.style = ''
    } else {
        link.style = 'filter:grayscale(1);pointer-events:none;'
    }
    let href = link.href;
    link.href = href.slice(0,href.indexOf('role=')+5)+newRole;
}