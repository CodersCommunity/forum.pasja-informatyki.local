import { sendAjax, AJAX_PURPOSE } from './ajaxService';
import reportReasonPopupDOMWrapper, {
  reportReasonPopupDOMReferences,
} from './popupFactory';
import { swapElement } from './misc';

const {
  reportReasonPopup,
  reportReasonPopupForm,
  customReportReason,
  reportReasonSuccessInfo,
  requirableFormElements,
  reportReasonValidationError,
} = reportReasonPopupDOMReferences;
const BTN_NAME_SUFFIXES_REGEX = /do(clear|un)?flag[s]?/;
const FLAG_BTN_NAME_SUFFIX = 'doflag';
// const doCommentInputNameSuffix = '_docomment';
const reportFlagMap = {
  regex: {
    question: /q_doflag/,
    answer: /^a(\d+)_doflag/,
    comment: /^c(\d+)_doflag/,
    doComment: /^a(\d+)_docomment/,
  },
  getPostIdFromInputName(postType, inputName) {
    // TODO: check if it works (changed exec to match)...
    const [, postId] = inputName.match(this.regex[postType]);
    return postId;
  },
  recognizeInputKindByName(inputName) {
    const [mappedInputNameRegexKey] = Object.entries(
      this.regex
    ).find(([regexKey, regexValue]) => regexValue.test(inputName));
    return mappedInputNameRegexKey;
  },
  collectForumPostMetaData() {
    const postType = this.recognizeInputKindByName(flagButtonDOM.name);
    const postRootSource = flagButtonDOM.form.getAttribute('action');
    const postMetaData = {
      questionId: postRootSource.split('/')[1],
      postType: postType.slice(0, 1),
    };
    postMetaData.postId =
      this.getPostIdFromInputName(postType, flagButtonDOM.name) ||
      postMetaData.questionId;

    // if (postType === 'answer') {
    //   postMetaData.answerId = this.getPostIdFromInputName(
    //     'answer',
    //     flagButtonDOM.name
    //   );
    // } else if (postType === 'comment') {

    // const doCommentInputDOM = flagButtonDOM.parentElement.querySelector(
    //   `[name*="${doCommentInputNameSuffix}"]`
    // );
    // postMetaData.answerId = this.getPostIdFromInputName(
    //   'doComment',
    //   doCommentInputDOM.name
    // );

    //   postMetaData.commentId = this.getPostIdFromInputName(
    //     'comment',
    //     flagButtonDOM.name
    //   );
    // }

    return postMetaData;
  },
};

let bootstrapUsed = false;
let flagButtonDOM = null;

const showReportReasonPopup = (originalFormActionAttribute) => {
  reportReasonPopupForm.action = originalFormActionAttribute;
  reportReasonPopupDOMWrapper.classList.add('report-reason-wrapper--show');
};
const hideReportReasonPopup = () => {
  reportReasonSuccessInfo.classList.remove(
    'report-reason-popup__success-info--show'
  );
  reportReasonPopupDOMWrapper.classList.remove('report-reason-wrapper--show');
  customReportReason.classList.remove(
    'report-reason-popup__custom-report-reason--show'
  );
  reportReasonPopup.classList.remove('report-reason-popup--hide');
  reportReasonValidationError.classList.remove(
    'report-reason-popup__validation-error--show'
  );
  reportReasonPopupForm.reset();
};
const bootstrapReportReasonPopup = () => {
  if (bootstrapUsed) {
    throw 'bootstrapReportReasonPopup should be called only once!';
  }

  initOffClickHandler();
  initReasonList();
  initButtons();
  initPopupContainer();

  bootstrapUsed = true;
};
bootstrapReportReasonPopup.handler = reportReasonFlagButtonHandler;

function reportReasonFlagButtonHandler(event) {
  if (event.target.name && event.target.name.endsWith(FLAG_BTN_NAME_SUFFIX)) {
    event.preventDefault();
    event.stopPropagation();

    handleFlagClick(event.target);
  }
}

function handleFlagClick(target) {
  flagButtonDOM = target;
  showReportReasonPopup(target.form.action);
}

function initOffClickHandler() {
  reportReasonPopupDOMWrapper.addEventListener('click', (event) => {
    const checkDOMElementsId = (DOMElement) =>
      DOMElement.id === 'reportReasonPopup' ||
      DOMElement.id === 'reportReasonSuccessInfo';
    const clickedOutsidePopup = !event.composedPath().some(checkDOMElementsId);

    if (clickedOutsidePopup) {
      hideReportReasonPopup();
    }
  });
}

function initReasonList() {
  const reasonList = reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonList'
  );
  reasonList.addEventListener('change', ({ target }) => {
    reportReasonValidationError.classList.remove(
      'report-reason-popup__validation-error--show'
    );

    if (reasonList.children[reasonList.children.length - 1].contains(target)) {
      customReportReason.classList.add(
        'report-reason-popup__custom-report-reason--show'
      );
      setTimeout(customReportReason.focus.bind(customReportReason));
    } else {
      customReportReason.classList.remove(
        'report-reason-popup__custom-report-reason--show'
      );
    }
  });
}

function initButtons() {
  const cancelButton = reportReasonPopupDOMWrapper.querySelector(
    '#cancelReportReason'
  );
  cancelButton.addEventListener('click', hideReportReasonPopup);

  const sendButton = reportReasonPopupDOMWrapper.querySelector(
    '#sendReportReason'
  );
  sendButton.addEventListener('click', submitForm);

  const closeReportReasonSentInfo = reportReasonPopupDOMWrapper.querySelector(
    '#closeReportReasonSentInfo'
  );
  closeReportReasonSentInfo.addEventListener('click', hideReportReasonPopup);
}

function initPopupContainer() {
  const popupContainer = document.querySelector('.qa-body-wrapper');
  popupContainer.appendChild(reportReasonPopupDOMWrapper);
}

function submitForm(event) {
  event.preventDefault();

  const sendButton = event.target;
  sendButton.blur();

  const isFormValid = validateForm(sendButton);
  if (!isFormValid) {
    return;
  }

  toggleSendWaitingState(sendButton, true);

  const formData = prepareFormData();
  sendAjax(formData, AJAX_PURPOSE.FLAG).then(
    (response) => {
      console.warn('response:', response);
      onAjaxSuccess(response, formData, sendButton);
    },
    (ajaxError) => onAjaxError(sendButton, ajaxError)
  );
}

function onAjaxSuccess(response, formData, sendButton) {
  toggleSendWaitingState(sendButton, false);
  updateCurrentPostFlags(response.currentFlags, formData);
  swapElement(
    flagButtonDOM,
    getUnflagButtonHTML({
      postType: formData.postType,
      questionId: formData.questionId,
      postId: formData.postId,
      parentId: getPostParentId(),
    })
  );
  showSuccessPopup();
}

function onAjaxError(sendButton, ajaxError) {
  toggleSendWaitingState(sendButton, false);
  // TODO: add proper error handling
  console.warn('ajaxError:', ajaxError);
}

function validateForm(sendButton) {
  const isAnyFormElementUsed = [...requirableFormElements].some((element) => {
    const isCheckedRadioInput =
      element.type === 'radio' && element.value !== 'custom' && element.checked;
    const isFilledTextArea =
      element.tagName.toLowerCase() === 'textarea' && element.value;

    return isCheckedRadioInput || isFilledTextArea;
  });

  if (!isAnyFormElementUsed) {
    notifyAboutValidationError(sendButton);
  }

  return isAnyFormElementUsed;
}

function notifyAboutValidationError(sendButton) {
  reportReasonValidationError.classList.add(
    'report-reason-popup__validation-error--show'
  );
  sendButton.classList.add(
    'report-reason-popup__button--save--validation-blink'
  );
  setTimeout(() => {
    sendButton.classList.remove(
      'report-reason-popup__button--save--validation-blink'
    );
  }, 1000);
}

function prepareFormData() {
  const reportMetaData = reportFlagMap.collectForumPostMetaData();
  const [reasonId, notice] = new FormData(reportReasonPopupForm).getAll(
    'reportReason'
  );

  return {
    ...reportMetaData,
    reasonId,
    notice,
    reportType: 'addFlag',
  };
}

function toggleSendWaitingState(buttonReference, isWaiting) {
  if (isWaiting) {
    buttonReference.disabled = true;
    window.qa_show_waiting_after(buttonReference, true);
  } else {
    window.qa_hide_waiting(buttonReference);
    buttonReference.disabled = false;
  }
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

  if (targetElement) {
    /*swapElement(targetElement, currentFlagsHTML);*/
    targetElement.outerHTML = currentFlagsHTML;
  } else {
    const responseAsDOM = new DOMParser()
      .parseFromString(currentFlagsHTML, 'text/html')
      .querySelector(targetElementSelector);
    flagsMetadataWrapper.appendChild(responseAsDOM);
  }
}

function showSuccessPopup() {
  reportReasonPopup.classList.add('report-reason-popup--hide');
  reportReasonSuccessInfo.classList.add(
    'report-reason-popup',
    'report-reason-popup__success-info--show'
  );
}

function getPostParentId() {
  const parentElement = flagButtonDOM.closest('[id*="_list"]');

  if (!parentElement) {
    return null;
  }

  const parentElementPostId = parentElement.id.slice(
    1,
    parentElement.id.indexOf('_')
  );
  return parentElementPostId;
}

function getUnflagButtonHTML({ postType, questionId, postId, parentId }) {
  switch (postType) {
    case 'q': {
      return `
        <input name="q_dounflag" 
          onclick="qa_show_waiting_after(this, false);" 
          value="wycofaj zgłoszenie" 
          title="Wycofaj zgłoszenie tej treści" 
          type="submit" 
          class="qa-form-light-button qa-form-light-button-unflag">
      `;
    }
    case 'a': {
      return `
        <input name="a${postId}_dounflag" 
            onclick="return qa_answer_click(${postId}, ${questionId}, this);" 
            value="wycofaj zgłoszenie" 
            title="Wycofaj zgłoszenie tej treści" 
            type="submit" 
            class="qa-form-light-button qa-form-light-button-unflag">
      `;
    }
    case 'c': {
      return `
        <input name="c${postId}_dounflag" 
            onclick="return qa_comment_click(${postId}, ${questionId}, ${parentId}, this);" 
            value="wycofaj zgłoszenie" 
            title="Wycofaj zgłoszenie tej treści" 
            type="submit" 
            class="qa-form-light-button qa-form-light-button-unflag">
      `;
    }
    default: {
      console.error(
        'Unrecognized postType!',
        postType,
        ' /questionId: ',
        questionId,
        ' /postId: ',
        postId
      );
    }
  }
}

export default bootstrapReportReasonPopup;
