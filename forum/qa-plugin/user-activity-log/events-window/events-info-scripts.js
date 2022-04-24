const openWindow = document.querySelector('.open-modal');
if(openWindow) {
    const modal = document.querySelector('.modal-content');
    const closeWindow = document.querySelector('.close-modal');

    openWindow.addEventListener('click', ()=>{
        modal.style.display = 'block';
        openWindow.style.display = 'none';
    }, false);

    closeWindow.addEventListener('click', ()=>{
        modal.style.display = 'none';
        openWindow.style.display = 'block';
    }, false);
}
