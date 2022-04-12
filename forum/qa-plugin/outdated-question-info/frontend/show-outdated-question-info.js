const showInfoAboutOutdatedQuestion = (placeOfOutdatedQuestionInfo) => {
    const publishDateSpan = document.querySelectorAll('.value-title')[2];

    const now = new Date();
    const publishDate = new Date(publishDateSpan.title);

    if(publishDate.getFullYear() < now.getFullYear()) {
        if(publishDate.getMonth()-1 >= now.getMonth()){
            if(!document.querySelector('.qa-outdated-question')) {
                placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', 
                        `<div class = "qa-outdated-question-container">
                                <span class = "qa-outdated-question-info">
                                        To pytanie zostało zadane ponad 2 miesiące temu i może być już nie aktualne.<br/>
                                        Zastanów się, czy na pewno chcesz "odkopać" to pytanie.
                                </span>
                        </div>`);
            }
        }
    }
}
if(document.querySelector('.qa-a-form')!== null){
    const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form')
    if(placeOfOutdatedQuestionInfo.getAttribute('style') === "display:none;"){
        document.querySelector('#q_doanswer').addEventListener('click', ()=>{
            showInfoAboutOutdatedQuestion(placeOfOutdatedQuestionInfo)
        });
    }else{
        showInfoAboutOutdatedQuestion(placeOfOutdatedQuestionInfo);
    }
}