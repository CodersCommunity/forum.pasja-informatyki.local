
const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form');

const publishDateSpan = document.querySelectorAll('.value-title')[2];

const now = new Date();
const publishDate = new Date(publishDateSpan.title);
//TODO: Fix this
console.log(publishDate.getMonth()-1 >= now.getMonth());
if(publishDate.getFullYear() < now.getFullYear()) {
    if(publishDate.getMonth()-2 > now.getMonth()){
        placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', `<div><h2>Hello</h2></div>`);
    }
}else{
    placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', `<div><h2>Bye</h2></div>`);
}