import { sendAjax, AJAX_PURPOSE } from './ajaxService';
import { swapElement } from './misc';

const questionFlagBtnHTML = `
    <input name="q_doflag" 
        onclick="qa_show_waiting_after(this, false);" 
        value="zgłoś" 
        type="submit" 
        class="qa-form-light-button qa-form-light-button-flag" 
        original-title="Zgłoś to pytanie jako spam lub niezgodne z regulaminem" 
        title="">
`;

function removeFlagFromQuestion(target) {
  window.qa_show_waiting_after(target, false);
  sendAjax(getRequestParams(target), AJAX_PURPOSE.UN_FLAG).then(
    (unFlagResult) => {
      console.warn('unFlagResult: ', unFlagResult);

      // TODO: just for tests
      const regRes = target.name.split('_')[0].match(/\d+/);
      const postType = regRes.input.slice(0, regRes.index);
      const postId = regRes[0];

      updateCurrentPostFlags(unFlagResult, {postType, postId});
      swapUnFlagBtnToFlagBtn(target);
    },
    (reason) => notifyRemovingFlagFailed(reason, target)
  );
}

function getRequestParams(target) {
  const requestParams = new FormData(target.form);
  requestParams.append(target.name, target.value);
  requestParams.append('prevent_refresh', 'true');

  return requestParams;
}

function swapUnFlagBtnToFlagBtn(unFlagBtn) {
  window.qa_hide_waiting(unFlagBtn);
  swapElement(unFlagBtn, questionFlagBtnHTML);
}

function updateCurrentPostFlags(currentFlagsHTML, {postType, postId})  {
  const flagsMetadataWrapper = postType === 'q' ?
      document.querySelector('.qa-q-view-meta') :
      document.querySelector(`#${postType}${postId} .qa-${postType}-item-meta`);
  const targetElementSelector = `.qa-${postType}-item-flags`;
  const targetElement = flagsMetadataWrapper.querySelector(targetElementSelector);

  targetElement.outerHTML = currentFlagsHTML;
}

function notifyRemovingFlagFailed(reason, unFlagBtn) {
  window.qa_hide_waiting(unFlagBtn);

  console.warn('notifyRemovingFlagFailed: /reason: ', reason);
}

export default removeFlagFromQuestion;
