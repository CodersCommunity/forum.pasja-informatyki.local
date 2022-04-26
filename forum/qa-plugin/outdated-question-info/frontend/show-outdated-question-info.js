const placeOfOutdatedQuestionInfo = document.querySelector('.qa-a-form');
if(placeOfOutdatedQuestionInfo){
    const publishDateSpan = document.querySelector('.published > .value-title');
    const now = new Date();
    const publishDate = new Date(publishDateSpan.title);
    const publishYearOlderThanNow = publishDate.getFullYear() < now.getFullYear();
    const publishMonthNewerThanNow = publishDate.getMonth() - 1 >= now.getMonth();

    if (publishYearOlderThanNow && publishMonthNewerThanNow) {
        let shouldBeDisplayed = "";
        if(placeOfOutdatedQuestionInfo.style.display === 'none'){
            shouldBeDisplayed = "hidden";
        }
        placeOfOutdatedQuestionInfo.insertAdjacentHTML('beforebegin', 
                `<p class = "qa-outdated-question-container ${shouldBeDisplayed}">
                    To pytanie zostało zadane już dawno temu i może być nieaktualne.<br/>
                    Upewnij się, że Twoja odpowiedź nadal będzie pomocna.
                </p>`);
    }
    const QuestionElemExist = document.querySelector('.qa-outdated-question-container');

    if(QuestionElemExist){
        const cancelAnswer = document.querySelectorAll('input[name=docancel]')[1];
        const outdatedInfoContainerClassList = QuestionElemExist.classList;

        cancelAnswer.addEventListener('click', ()=>{
            outdatedInfoContainerClassList.toggle('hidden');
        }, false)

        document.querySelector('#q_doanswer').addEventListener('click', ()=>{
            outdatedInfoContainerClassList.toggle('hidden');
        })
    }
}