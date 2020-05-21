import { sendAjax, AJAX_PURPOSE } from "./ajaxService";

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

function removeFlagFromQuestion(event) {
    event.preventDefault();
    event.stopPropagation();

    const {target} = event;

    window.qa_show_waiting_after(target, false);
    sendAjax(getRequestParams(target), AJAX_PURPOSE.UN_FLAG)
        .then(() => swapUnFlagBtnToFlagBtn(target), (reason) => notifyRemovingFlagFailed(reason, target));
}

function getRequestParams(target) {
    const requestParams = new FormData(target.form);
    requestParams.append(target.name, target.value);

    return requestParams;
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

const handleRemovingFlagsFromQuestion = () => {
    return;
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', handleRemovingFlagsFromQuestion, {once: true});
        return;
    }

    console.warn('???: ', document.querySelectorAll('[name="q_dounflag"], [name="q_doclearflags"]'));
    [...document.querySelectorAll('[name="q_dounflag"], [name="q_doclearflags"]')]
        .forEach(btn => {
            if (btn) {
                // Get rid of available buttons "onclick"
                btn.setAttribute( 'onclick', noop );
                btn.onclick = noop;

                btn.addEventListener( 'click', removeFlagFromQuestion, true);
            }
        });
};
handleRemovingFlagsFromQuestion();

export default handleRemovingFlagsFromQuestion;
