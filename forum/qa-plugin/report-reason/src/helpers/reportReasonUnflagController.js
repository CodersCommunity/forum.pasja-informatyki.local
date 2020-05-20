import sendAjax from "./ajaxService";

const noop = () => {};
const questionFlagBtnHTML = `
    <input name="q_doflag" 
        onclick="qa_show_waiting_after(this, false);" 
        value="zgłoś" 
        type="submit" 
        class="qa-form-light-button qa-form-light-button-flag" 
        original-title="Zgłoś to pytanie jako spam lub niezgodne z regulaminem" 
        title="">
`;

function removeFlagFromQuestion({target}) {
    window.qa_show_waiting_after(target, false);

    const requestParams = new FormData(target.form);
    requestParams.append(target.name, target.value);

    sendAjax(window.location.origin, requestParams, AJAX_PURPOSE.UNFLAG)
        .then(() => swapUnFlagBtnToFlagBtn(target), (reason) => notifyRemovingFlagFailed(reason, target));

}

function swapUnFlagBtnToFlagBtn(unFlagBtn) {
    window.qa_hide_waiting(unFlagBtn);

    unFlagBtn.outerHTML = questionFlagBtnHTML;
    unFlagBtn.addEventListener('click', ({target}) => {
        console.warn('swapped unflag clicked: ', target);
    });
}

function notifyRemovingFlagFailed(reason, unFlagBtn) {
    window.qa_hide_waiting(unFlagBtn);

    console.warn('notifyRemovingFlagFailed: /reason: ' ,reason);
}

const handleRemovingFlagsFromQuestion = (unFlagQuestionBtn, clearFlagsQuestionBtn) => {
    // Get rid of available buttons "onclick"
    [unFlagQuestionBtn, clearFlagsQuestionBtn].forEach(btn => {
        btn.setAttribute('onclick', noop);
        btn.onclick = noop;
    });

    unFlagQuestionBtn = unFlagQuestionBtn || document.querySelector('[name="q_dounflag"]');
    unFlagQuestionBtn.addEventListener('click', removeFlagFromQuestion);

    clearFlagsQuestionBtn = clearFlagsQuestionBtn || document.querySelector('[name="q_doclearflags"]');
    clearFlagsQuestionBtn.addEventListener('click', removeFlagFromQuestion);
};
handleRemovingFlagsFromQuestion();

export default handleRemovingFlagsFromQuestion;
