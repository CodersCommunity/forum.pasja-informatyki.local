/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/helpers/ajaxService.js":
/*!************************************!*\
  !*** ./src/helpers/ajaxService.js ***!
  \************************************/
/*! exports provided: sendAjax, AJAX_PURPOSE */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendAjax", function() { return sendAjax; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AJAX_PURPOSE", function() { return AJAX_PURPOSE; });
const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = {
  FLAG: '/ajaxflagger',
  UN_FLAG: window.location.href
};
const CONTENT_TYPE = {
  FLAG: 'application/json',
  UN_FLAG: 'application/x-www-form-urlencoded'
};

const AJAX_PURPOSE = Object.freeze({
  FLAG: 'FLAG',
  UN_FLAG: 'UN_FLAG'
});

function prepareBody(data, purpose) {
  return purpose === AJAX_PURPOSE.FLAG ? JSON.stringify(data) : data;
}

const sendAjax = (data, purpose) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    fetch('/ajaxflagger'/*URL[purpose]*/, {
      method: 'POST',
      headers: {
        // 'Content-Type': CONTENT_TYPE[purpose] // 'application/json' // 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: JSON.stringify(data) // prepareBody(data, purpose)
    }).then(async (value) => {
      clearTimeout(timeoutId);

      let text = null;
      if (purpose === AJAX_PURPOSE.UN_FLAG) {
        try {
          text = await value.text();
        } catch ( e ) {
          console.error( 'value.text not worked... /e: ', e );
        }
      }

      console.warn('fetch response: ', value, ' /?:', text);

      const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';
      resolve(resolveValue);
    });
  });
};




/***/ }),

/***/ "./src/helpers/misc.js":
/*!*****************************!*\
  !*** ./src/helpers/misc.js ***!
  \*****************************/
/*! exports provided: swapElement, elementsHTMLMap */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "swapElement", function() { return swapElement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "elementsHTMLMap", function() { return elementsHTMLMap; });
const swapElement = (referenceNode, html) => {
  const tmpParent = document.createElement('div');
  tmpParent.innerHTML = html;

  const newElement = tmpParent.removeChild(tmpParent.firstElementChild);
  referenceNode.parentNode.insertBefore(newElement, referenceNode);
  referenceNode.remove();

  // return newElement;
};

const elementsHTMLMap = new Map([
  [
    'textarea',
    `<textarea id="customReportReason" rows="3" cols="47" name="reportReason" class="report-reason-popup__custom-report-reason" data-requirable="true"></textarea>`,
  ],
  [
    'getListItem',
    ({ reasonKey, reasonValue, index, isLast, textAreaDOM }) => {
      return `
            <li>
                <label for="${reasonKey}">
                    <input id="${reasonKey}" 
                            type="radio" 
                            value="${index}" 
                            name="reportReason" 
                            data-requirable="true">
                    ${reasonValue}
                </label>
                ${isLast ? textAreaDOM : ''}
            </li>
        `;
    },
  ],
  [
    'getPopupWrapper',
    (listItemsDOM) => {
      return `
            <div id="reportReasonPopup" class="report-reason-popup">
                <p>Zaznacz powód zgłoszenia lub podaj własny:</p>
                
                <form method="post" class="report-reason-popup__form">
                    <ul id="reportReasonList" class="report-reason-popup__list">${listItemsDOM}</ul>
                
                    <p id="reportReasonValidationError" class="report-reason-popup__validation-error">Nie zaznaczono powodu zgłoszenia!</p>
                    
                    <input id="cancelReportReason" type="button" value="Anuluj" class="report-reason-popup__button report-reason-popup__button--cancel">
                    <button id="sendReportReason" type="submit" class="report-reason-popup__button report-reason-popup__button--save">Wyślij</button>
                </form>
            </div>
            <div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
                Zgłoszenie zostało wysłane.
                <button id="closeReportReasonSentInfo" class="report-reason-popup__button report-reason-popup__button--close" type="button">Zamknij</button>
            </div>`;
    },
  ],
]);


/***/ }),

/***/ "./src/helpers/popupController.js":
/*!****************************************!*\
  !*** ./src/helpers/popupController.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ajaxService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ajaxService */ "./src/helpers/ajaxService.js");
/* harmony import */ var _popupFactory__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./popupFactory */ "./src/helpers/popupFactory.js");
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./misc */ "./src/helpers/misc.js");
/* harmony import */ var _unflagController__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./unflagController */ "./src/helpers/unflagController.js");





const {
  reportReasonPopup,
  reportReasonPopupForm,
  customReportReason,
  reportReasonSuccessInfo,
  requirableFormElements,
  reportReasonValidationError,
} = _popupFactory__WEBPACK_IMPORTED_MODULE_1__["reportReasonPopupDOMReferences"];
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
  getPostIdFromInputName( postType, inputName) {
    // TODO: check if it works (changed exec to match)...
    const [, postId] = inputName.match(this.regex[postType]);
    return postId;
  },
  recognizeInputKindByName(inputName) {
    const [mappedInputNameRegexKey] = Object.entries(this.regex).find(
      ([regexKey, regexValue]) => regexValue.test(inputName));
    return mappedInputNameRegexKey;
  },
  collectForumPostMetaData() {
    const postType = this.recognizeInputKindByName(flagButtonDOM.name);
    const postRootSource = flagButtonDOM.form.getAttribute('action');
    const postMetaData = {
      questionId: postRootSource.split('/')[1],
      postType: postType.slice(0, 1),
    };
    postMetaData.postId = this.getPostIdFromInputName(postType, flagButtonDOM.name) || postMetaData.questionId;

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
  _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].classList.add('report-reason-wrapper--show');
};
const hideReportReasonPopup = () => {
  reportReasonSuccessInfo.classList.remove(
    'report-reason-popup__success-info--show'
  );
  _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].classList.remove('report-reason-wrapper--show');
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
  // TODO: check if it can also be used to handle unflag clicks
  if (event.target.name && BTN_NAME_SUFFIXES_REGEX.test(event.target.name)) {
    event.preventDefault();
    event.stopPropagation();

    if (event.target.name.endsWith(FLAG_BTN_NAME_SUFFIX)) {
      handleFlagClick( event.target );
    } else {
      Object(_unflagController__WEBPACK_IMPORTED_MODULE_3__["default"])(event.target);
    }
  }
}

function handleFlagClick(target) {
  flagButtonDOM = target;
  showReportReasonPopup(target.form.action);
}

function initOffClickHandler() {
  _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].addEventListener('click', (event) => {
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
  const reasonList = _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
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
  const cancelButton = _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#cancelReportReason'
  );
  cancelButton.addEventListener('click', hideReportReasonPopup);

  const sendButton = _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#sendReportReason'
  );
  sendButton.addEventListener('click', submitForm);

  const closeReportReasonSentInfo = _popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#closeReportReasonSentInfo'
  );
  closeReportReasonSentInfo.addEventListener('click', hideReportReasonPopup);
}

function initPopupContainer() {
  const popupContainer = document.querySelector('.qa-body-wrapper');
  popupContainer.appendChild(_popupFactory__WEBPACK_IMPORTED_MODULE_1__["default"]);
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
  Object(_ajaxService__WEBPACK_IMPORTED_MODULE_0__["sendAjax"])(formData, _ajaxService__WEBPACK_IMPORTED_MODULE_0__["AJAX_PURPOSE"].FLAG).then(
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
  Object(_misc__WEBPACK_IMPORTED_MODULE_2__["swapElement"])(flagButtonDOM, getUnflagButtonHTML({
    postType: formData.postType,
    questionId: formData.questionId,
    postId: formData.postId,
    parentId: getPostParentId()
  })); //.addEventListener('click', removeFlagFromQuestion);
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
  const [reasonId, notice] = new FormData(reportReasonPopupForm).getAll('reportReason');

  return {
    ...reportMetaData,
    reasonId, notice,
    reportType: 'addFlag'
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

function updateCurrentPostFlags(currentFlagsHTML, {postType, postId}) {
  const flagsMetadataWrapper = postType === 'q' ?
      document.querySelector('.qa-q-view-meta') :
      document.querySelector(`#${postType}${postId} .qa-${postType}-item-meta`);
  const targetElementSelector = `.qa-${postType}-item-flags`;
  const targetElement = flagsMetadataWrapper.querySelector(targetElementSelector);

  if (targetElement) {
    /*swapElement(targetElement, currentFlagsHTML);*/
    targetElement.outerHTML = currentFlagsHTML;
  } else {
    const responseAsDOM = new DOMParser().parseFromString(currentFlagsHTML, 'text/html').querySelector(targetElementSelector);
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

  const parentElementPostId = parentElement.id.slice(1, parentElement.id.indexOf('_'));
  return parentElementPostId;
}

function getUnflagButtonHTML({postType, questionId, postId, parentId}) {
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
      console.error('Unrecognized postType!', postType, ' /questionId: ', questionId, ' /postId: ', postId);
    }
  }
}

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);


/***/ }),

/***/ "./src/helpers/popupFactory.js":
/*!*************************************!*\
  !*** ./src/helpers/popupFactory.js ***!
  \*************************************/
/*! exports provided: reportReasonPopupDOMReferences, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reportReasonPopupDOMReferences", function() { return reportReasonPopupDOMReferences; });
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./misc */ "./src/helpers/misc.js");


const reportReasonPopupDOMWrapper = (function () {
    const listItemsDOM = Object.entries(FLAG_REASONS_MAP).reduce(
        (listItems, [reasonKey, reasonValue], index, flagReasonsCollection) => {
            // const reasonItemId = `reportReasonItem${index}`;
            const isLast = index === flagReasonsCollection.length - 1;
            const textAreaDOM = isLast && _misc__WEBPACK_IMPORTED_MODULE_0__["elementsHTMLMap"].get('textarea');

            return listItems + _misc__WEBPACK_IMPORTED_MODULE_0__["elementsHTMLMap"].get('getListItem')({reasonKey, reasonValue, index, isLast, textAreaDOM});
        },
        ''
    );

    const popupWrapper = document.createElement('div');
    popupWrapper.classList.add('report-reason-wrapper');
    popupWrapper.innerHTML = _misc__WEBPACK_IMPORTED_MODULE_0__["elementsHTMLMap"].get('getPopupWrapper')(listItemsDOM);

    return popupWrapper;
})();

const reportReasonPopupDOMReferences = {
  reportReasonPopup: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonPopup'
  ),
  reportReasonPopupForm: reportReasonPopupDOMWrapper.querySelector('form'),
  customReportReason: reportReasonPopupDOMWrapper.querySelector(
    '#customReportReason'
  ),
  reportReasonSuccessInfo: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonSuccessInfo'
  ),
  requirableFormElements: reportReasonPopupDOMWrapper.querySelectorAll(
    '[data-requirable="true"]'
  ),
  reportReasonValidationError: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonValidationError'
  ),
};

/* harmony default export */ __webpack_exports__["default"] = (reportReasonPopupDOMWrapper);


/***/ }),

/***/ "./src/helpers/unflagController.js":
/*!*****************************************!*\
  !*** ./src/helpers/unflagController.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ajaxService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ajaxService */ "./src/helpers/ajaxService.js");
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./misc */ "./src/helpers/misc.js");



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
  Object(_ajaxService__WEBPACK_IMPORTED_MODULE_0__["sendAjax"])(getRequestParams(target), _ajaxService__WEBPACK_IMPORTED_MODULE_0__["AJAX_PURPOSE"].UN_FLAG).then(
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

      updateCurrentPostFlags(unFlagResult, { postType, postId });
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
  Object(_misc__WEBPACK_IMPORTED_MODULE_1__["swapElement"])(unFlagBtn, questionFlagBtnHTML);
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

/* harmony default export */ __webpack_exports__["default"] = (removeFlagFromQuestion);


/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_popupController__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/popupController */ "./src/helpers/popupController.js");
// TODO: load it lazily


document.addEventListener(
  'DOMContentLoaded',
  function initReportReasonPlugin() {
    Object(_helpers_popupController__WEBPACK_IMPORTED_MODULE_0__["default"])();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener(
      'click',
      _helpers_popupController__WEBPACK_IMPORTED_MODULE_0__["default"].handler,
      true /* use capture phase to fire handler before Q2A listeners on (un)flag buttons will */
    );
  }
);


/***/ })

/******/ });
//# sourceMappingURL=script.js.map