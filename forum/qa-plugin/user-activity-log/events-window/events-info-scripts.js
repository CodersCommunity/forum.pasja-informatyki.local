const modal = document.querySelector('.modal-content');
const closeWindow = document.querySelector('.close-modal');
const openWindow = document.querySelector('.open-modal');

openWindow.addEventListener('click', ()=>{
    modal.style.display = 'block';
}, false);

closeWindow.addEventListener('click', ()=>{
    modal.style.display = 'none';
}, false);
