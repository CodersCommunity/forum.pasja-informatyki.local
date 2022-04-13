const showInfoAboutOutdatedQuestion = () => {
    const publishDateSpan = document.querySelector('.published > .value-title');
    const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form');

    const now = new Date();
    const publishDate = new Date(publishDateSpan.title);

    const publishYearOlderThanNow = publishDate.getFullYear() < now.getFullYear();
    const publishMonthNewerThanNow = publishDate.getMonth() - 1 >= now.getMonth();
    const doesQuestionElemExist = document.querySelector('.qa-outdated-question-container');

    if (publishYearOlderThanNow && publishMonthNewerThanNow && !doesQuestionElemExist) {
        placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', 
                `<p class = "qa-outdated-question-container">
                        To pytanie zostało zadane ponad 2 miesiące temu i może być już nieaktualne.<br/>
                        Zastanów się, czy na pewno chcesz "odkopać" to pytanie.
                </p>`);
    }
}
if(document.querySelector('.qa-a-form')){
    const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form');
    if(placeOfOutdatedQuestionInfo.getAttribute('style') === "display:none;"){
        document.querySelector('#q_doanswer').addEventListener('click', showInfoAboutOutdatedQuestion);
    }else{
        showInfoAboutOutdatedQuestion();
    }
}