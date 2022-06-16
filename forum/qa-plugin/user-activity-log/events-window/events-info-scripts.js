const openWindow = document.querySelector('.open-modal');
if(openWindow) {
    const modalClassName = document.querySelector('.modal-content').classList;
    const closeWindow = document.querySelector('.close-modal');

    openWindow.addEventListener('click', ()=>{
        modalClassName.remove('hidden');
        openWindow.classList.add('hidden');
        
    }, false);

    closeWindow.addEventListener('click', ()=>{
        modalClassName.add('hidden');
        openWindow.classList.remove('hidden');
    }, false);
}
