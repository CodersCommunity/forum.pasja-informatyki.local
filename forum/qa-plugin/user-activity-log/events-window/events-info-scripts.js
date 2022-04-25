const openWindow = document.querySelector('.open-modal');
if(openWindow) {
    const modal = document.querySelector('.modal-content').classList;
    const closeWindow = document.querySelector('.close-modal');

    openWindow.addEventListener('click', ()=>{
        modal.remove('hidden');
        modal.add('shown');

        openWindow.classList.remove('shown');
        openWindow.classList.add('hidden');
        
    }, false);

    closeWindow.addEventListener('click', ()=>{
        modal.remove('shown');
        modal.add('hidden');

        openWindow.classList.remove('hidden');
        openWindow.classList.add('shown');
    }, false);
}
