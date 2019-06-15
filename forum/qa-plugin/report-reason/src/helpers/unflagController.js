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

      let postType;
      let postId;

      if (!regRes) {
        postType = 'q';
        postId = window.location.pathname.split('/').find(Number);
      } else {
        postType = regRes.input.slice(0, regRes.index);
        postId = regRes[0];
      }

      updateCurrentPostFlags(unFlagResult.currentFlags, { postType, postId });
      swapUnFlagBtnToFlagBtn(target);
    },
    (reason) => notifyRemovingFlagFailed(reason, target)
  );
}

function getRequestParams(target) {
  const requestParams = {
      reportType: 'removeFlag',
      code: target.form.elements.code.value,
      questionId: window.location.pathname.split('/').find(Number),
      postType: target.name.slice(0, 1),
      action: target.name.split('_')[1].slice(2)
      // prevent_refresh: true,
  }; // new FormData(target.form);

    requestParams.postId = target.name.startsWith('q') ? requestParams.questionId : target.closest('.hentry').id.slice(1)

  // requestParams.append(target.name, target.value);
  // requestParams.append('prevent_refresh', 'true');
  // requestParams.append('reportType','addFlag');

  return requestParams;
}

function swapUnFlagBtnToFlagBtn(unFlagBtn) {
  window.qa_hide_waiting(unFlagBtn);
  swapElement(unFlagBtn, questionFlagBtnHTML);
}

function updateCurrentPostFlags(currentFlagsHTML, { postType, postId }) {
  const flagsMetadataWrapper =
    postType === 'q'
      ? document.querySelector('.qa-q-view-meta')
      : document.querySelector(
          `#${postType}${postId} .qa-${postType}-item-meta`
        );
  const targetElementSelector = `.qa-${postType}-item-flags`;
  const targetElement = flagsMetadataWrapper.querySelector(
    targetElementSelector
  );

  targetElement.outerHTML = currentFlagsHTML;
}

function notifyRemovingFlagFailed(reason, unFlagBtn) {
  window.qa_hide_waiting(unFlagBtn);

  console.warn('notifyRemovingFlagFailed: /reason: ', reason);
}

export default removeFlagFromQuestion;
