const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form');
if(placeOfOutdatedQuestionInfo){
    const publishDateSpan = document.querySelector('.published > .value-title');
    const now = new Date();
    const publishDate = new Date(publishDateSpan.title);
    const publishYearOlderThanNow = publishDate.getFullYear() < now.getFullYear();
    const publishMonthNewerThanNow = publishDate.getMonth() - 1 >= now.getMonth();

    if (publishYearOlderThanNow && publishMonthNewerThanNow) {
        let infoDisplay = "";
        if(placeOfOutdatedQuestionInfo.style.display !== 'none'){
            infoDisplay = "display:block;";
        }else{
            infoDisplay = "display:none;";
        }
        placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', 
                `<p class = "qa-outdated-question-container" style = "${infoDisplay}">
                    To pytanie zostało zadane już dawno temu i może być nieaktualne.<br/>
                    Upewnij się, że Twoja odpowiedź nadal będzie pomocna.
                </p>`);
    }
    const QuestionElemExist = document.querySelector('.qa-outdated-question-container');

    if(QuestionElemExist){
        document.querySelector('#q_doanswer').addEventListener('click', ()=>{
            QuestionElemExist.style.display = (QuestionElemExist.style.display === 'block') ? 'none' : 'block';
        })
    }
}