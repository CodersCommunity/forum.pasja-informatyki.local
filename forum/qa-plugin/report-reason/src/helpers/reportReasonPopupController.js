import sendAjax from './ajaxService';
import reportReasonPopupDOMWrapper, {
  reportReasonPopupDOMReferences,
} from './reportReasonPopupCreator';

const {
  reportReasonPopup,
  reportReasonPopupForm,
  customReportReason,
  reportReasonSuccessInfo,
  requirableFormElements,
  reportReasonValidationError,
} = reportReasonPopupDOMReferences;
const flagButtonNamePart = 'doflag';
// const doCommentInputNameSuffix = '_docomment';
const reportFlagMap = {
  regex: {
    question: /q_doflag/,
    answer: /^a(\d+)_doflag/,
    comment: /^c(\d+)_doflag/,
    doComment: /^a(\d+)_docomment/,
  },
  getPostIdFromInputName( postType, inputName) {
    const [, postId] = this.regex[postType].exec(inputName);
    return postId;
  },
  recognizeInputKindByName(inputName) {
    const [mappedInputNameRegexKey] = Object.entries(this.regex).find(
      ([regexKey, regexValue]) => regexValue.test(inputName));
    return mappedInputNameRegexKey;
  },
  collectForumPostMetaData(inputDOM) {
    const postType = this.recognizeInputKindByName(inputDOM.name);
    const postRootSource = inputDOM.form.getAttribute('action');
    const postMetaData = {
      questionId: postRootSource.split('/')[1],
      postType: postType.slice(0, 1),
    };
    postMetaData.postId = this.getPostIdFromInputName(postType, inputDOM.name) || postMetaData.questionId;

    // if (postType === 'answer') {
    //   postMetaData.answerId = this.getPostIdFromInputName(
    //     'answer',
    //     inputDOM.name
    //   );
    // } else if (postType === 'comment') {

      // const doCommentInputDOM = inputDOM.parentElement.querySelector(
      //   `[name*="${doCommentInputNameSuffix}"]`
      // );
      // postMetaData.answerId = this.getPostIdFromInputName(
      //   'doComment',
      //   doCommentInputDOM.name
      // );

    //   postMetaData.commentId = this.getPostIdFromInputName(
    //     'comment',
    //     inputDOM.name
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
  if (event.target.name && event.target.name.includes(flagButtonNamePart)) {
    event.preventDefault();
    event.stopPropagation();
    flagButtonDOM = event.target;
    showReportReasonPopup(event.target.form.action);
  }
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
  sendAjax(prepareFormData()).then(
    (value) => {
      console.warn('value:', value);
      onAjaxSuccess(sendButton);
    },
    (ajaxError) => onAjaxError(sendButton, ajaxError)
  );
}

function onAjaxSuccess(sendButton) {
  toggleSendWaitingState(sendButton, false);
  reportReasonPopup.classList.add('report-reason-popup--hide');
  reportReasonSuccessInfo.classList.add(
    'report-reason-popup',
    'report-reason-popup__success-info--show'
  );
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
  const reportMetaData = reportFlagMap.collectForumPostMetaData(flagButtonDOM);
  const [reasonId, notice] = new FormData(reportReasonPopupForm).getAll('reportReason');

  return {
    ...reportMetaData,
    reasonId, notice
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

export default bootstrapReportReasonPopup;
