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
/******/ 	return __webpack_require__(__webpack_require__.s = "./index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./index.js":
/*!******************!*\
  !*** ./index.js ***!
  \******************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_popupController__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./src/popupController */ "./src/popupController.js");
// TODO: load it lazily


document.addEventListener(
  'DOMContentLoaded',
  function initReportReasonPlugin() {
    Object(_src_popupController__WEBPACK_IMPORTED_MODULE_0__["default"])();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener(
      'click',
      _src_popupController__WEBPACK_IMPORTED_MODULE_0__["default"].handler,
      true /* use capture phase to fire handler before Q2A listeners on (un)flag buttons will */
    );
  }
);


/***/ }),

/***/ "./src/ajaxService.js":
/*!****************************!*\
  !*** ./src/ajaxService.js ***!
  \****************************/
/*! exports provided: sendAjax, AJAX_PURPOSE */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "sendAjax", function() { return sendAjax; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "AJAX_PURPOSE", function() { return AJAX_PURPOSE; });
const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = '/report-flag';
const CONTENT_TYPE = {
  FLAG: 'application/json',
  UN_FLAG: 'application/x-www-form-urlencoded',
};

const AJAX_PURPOSE = Object.freeze({
  FLAG: 'FLAG',
  UN_FLAG: 'UN_FLAG',
});

function prepareBody(data, purpose) {
  return purpose === AJAX_PURPOSE.FLAG ? JSON.stringify(data) : data;
}

const sendAjax = (data, purpose) => {
  return new Promise((resolve, reject) => {
    const timeoutId = setTimeout(() => {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);

    fetch(URL, {
      method: 'POST',
      headers: {
        // 'Content-Type': CONTENT_TYPE[purpose] // 'application/json' // 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: JSON.stringify(data), // prepareBody(data, purpose)
    }).then(async (value) => {
      clearTimeout(timeoutId);

      // let text = null;
      // if (purpose === AJAX_PURPOSE.UN_FLAG) {
      //   try {
      //     text = await value.text();
      //   } catch ( e ) {
      //     console.error( 'value.text not worked... /e: ', e );
      //   }
      // }

      console.warn('fetch response: ', value /*, ' /?:', text*/);

      // const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';
      resolve(value.json());
    });
  });
};




/***/ }),

/***/ "./src/misc.js":
/*!*********************!*\
  !*** ./src/misc.js ***!
  \*********************/
/*! exports provided: swapElement, elementsHTMLMap */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "swapElement", function() { return swapElement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "elementsHTMLMap", function() { return elementsHTMLMap; });
const { NOTICE_LENGTH, POPUP_LABELS } = FLAG_REASONS_METADATA;

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
    `<textarea id="customReportReason"
        class="report-reason-popup__custom-report-reason"
        name="reportReason"
        data-requirable="true"
        maxlength="${NOTICE_LENGTH}"
        rows="3"
        cols="47"></textarea>`,
  ],
  [
    'getListItem',
    ({ reasonKey, reasonValue, index, isLast, textAreaDOM }) => {
      return `
            <!-- TODO: handle checking inputs while tabbing -->
            <li tabindex="1">
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
                <p>${POPUP_LABELS.HEADER}</p>
                
                <form method="post" class="report-reason-popup__form">
                    <ul id="reportReasonList" class="report-reason-popup__list">${listItemsDOM}</ul>
                
                    <p id="reportReasonValidationError" class="report-reason-popup__validation-error">${POPUP_LABELS.NO_REASON_CHECKED}</p>
                    
                    <!-- TODO: why its input not button? -->
                    <input id="cancelReportReason" type="button" value="${POPUP_LABELS.CANCEL}" class="report-reason-popup__button report-reason-popup__button--cancel">
                    <button id="sendReportReason" type="submit" class="report-reason-popup__button report-reason-popup__button--save">${POPUP_LABELS.SEND}</button>
                </form>
            </div>
            <div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
                ${POPUP_LABELS.REPORT_SENT}
                <button id="closeReportReasonSentInfo" class="report-reason-popup__button report-reason-popup__button--close" type="button">${POPUP_LABELS.CLOSE}</button>
            </div>`;
    },
  ],
]);


/***/ }),

/***/ "./src/popupController.js":
/*!********************************!*\
  !*** ./src/popupController.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ajaxService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ajaxService */ "./src/ajaxService.js");
/* harmony import */ var _popupFactory__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./popupFactory */ "./src/popupFactory.js");
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./misc */ "./src/misc.js");




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
  Object(_misc__WEBPACK_IMPORTED_MODULE_2__["swapElement"])(
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

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);


/***/ }),

/***/ "./src/popupFactory.js":
/*!*****************************!*\
  !*** ./src/popupFactory.js ***!
  \*****************************/
/*! exports provided: reportReasonPopupDOMReferences, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reportReasonPopupDOMReferences", function() { return reportReasonPopupDOMReferences; });
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./misc */ "./src/misc.js");


const reportReasonPopupDOMWrapper = (function () {
  const listItemsDOM = Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce(
    (listItems, [reasonKey, reasonValue], index, flagReasonsCollection) => {
      // const reasonItemId = `reportReasonItem${index}`;
      const isLast = index === flagReasonsCollection.length - 1;
      const textAreaDOM = isLast && _misc__WEBPACK_IMPORTED_MODULE_0__["elementsHTMLMap"].get('textarea');

      return (
        listItems +
        _misc__WEBPACK_IMPORTED_MODULE_0__["elementsHTMLMap"].get('getListItem')({
          reasonKey,
          reasonValue,
          index,
          isLast,
          textAreaDOM,
        })
      );
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


/***/ })

/******/ });
//# sourceMappingURL=script.js.map