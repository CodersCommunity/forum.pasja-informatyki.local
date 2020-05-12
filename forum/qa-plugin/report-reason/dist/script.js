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

/***/ "./src/data/reasonsCollection.json":
/*!*****************************************!*\
  !*** ./src/data/reasonsCollection.json ***!
  \*****************************************/
/*! exports provided: 0, 1, 2, 3, 4, 5, 6, default */
/***/ (function(module) {

module.exports = JSON.parse("[{\"value\":\"spam\",\"description\":\"SPAM\"},{\"value\":\"insult\",\"description\":\"Wypowiedź jest obraźliwa\"},{\"value\":\"incorrectDescription\",\"description\":\"Nieprawidłowy temat/kategoria/otagowanie\"},{\"value\":\"misunderstoodContent\",\"description\":\"Niepełna lub niezrozumiała treść\"},{\"value\":\"duplicate\",\"description\":\"Duplikat pytania\"},{\"value\":\"codeNotInBlock\",\"description\":\"Kod nie jest umieszczony w odpowiednim bloczku\"},{\"value\":\"custom\",\"description\":\"Inny (dodaj opis)\"}]");

/***/ }),

/***/ "./src/helpers/ajaxService.js":
/*!************************************!*\
  !*** ./src/helpers/ajaxService.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
const httpTimeoutReason = 'Ajax timeout';

const ajax = (url, data, timeout) => {
  return new Promise((resolve, reject) => {
    fetch(url, {
      method: 'POST',
      body: data,
    }).then(resolve, reject);

    setTimeout(() => {
      reject(httpTimeoutReason);
    }, timeout);
  });
};

/* harmony default export */ __webpack_exports__["default"] = (ajax);


/***/ }),

/***/ "./src/helpers/reportReasonPopupController.js":
/*!****************************************************!*\
  !*** ./src/helpers/reportReasonPopupController.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ajaxService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ajaxService */ "./src/helpers/ajaxService.js");
/* harmony import */ var _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./reportReasonPopupCreator */ "./src/helpers/reportReasonPopupCreator.js");



const {
  reportReasonPopup,
  reportReasonPopupForm,
  customReportReason,
  reportReasonSuccessInfo,
  requirableFormElements,
  reportReasonValidationError,
} = _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["reportReasonPopupDOMReferences"];
const responseWaitTimeoutMs = 5000;
const flagButtonNamePart = 'doflag';
const doCommentInputNameSuffix = '_docomment';
const reportFlagMap = {
  regex: {
    question: /q_doflag/,
    answer: /^a(\d+)_doflag/,
    comment: /^c(\d+)_doflag/,
    doComment: /^a(\d+)_docomment/,
  },
  getNumberFromInputName(regexKey, inputName) {
    return (this.regex[regexKey].exec(inputName) || [])[1];
  },
  recognizeInputKindByName(inputName) {
    const mappedInputNameRegexKey = Object.entries(this.regex).find(
      ([regexKey, regexValue]) => {
        return regexValue.test(inputName);
      }
    )[0];
    return mappedInputNameRegexKey;
  },
  collectForumPostMetaData(inputDOM) {
    const postKind = this.recognizeInputKindByName(inputDOM.name);
    const postRootSource = inputDOM.form.getAttribute('action');
    const postMetaData = {
      rootId: postRootSource.split('/')[1],
    };

    if (postKind === 'answer') {
      postMetaData.answerId = this.getNumberFromInputName(
        'answer',
        inputDOM.name
      );
    } else if (postKind === 'comment') {
      const doCommentInputDOM = inputDOM.parentElement.querySelector(
        `[name*="${doCommentInputNameSuffix}"]`
      );
      postMetaData.answerId = this.getNumberFromInputName(
        'doComment',
        doCommentInputDOM.name
      );
      postMetaData.commentId = this.getNumberFromInputName(
        'comment',
        inputDOM.name
      );
    }

    return postMetaData;
  },
};

let bootstrapUsed = false;
let flagButtonDOM = null;

const showReportReasonPopup = (originalFormActionAttribute) => {
  reportReasonPopupForm.action = originalFormActionAttribute;
  _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].classList.add('report-reason-wrapper--show');
};
const hideReportReasonPopup = () => {
  reportReasonSuccessInfo.classList.remove(
    'report-reason-popup__success-info--show'
  );
  _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].classList.remove('report-reason-wrapper--show');
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
  _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].addEventListener('click', (event) => {
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
  const reasonList = _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
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
  const cancelButton = _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#cancelReportReason'
  );
  cancelButton.addEventListener('click', hideReportReasonPopup);

  const sendButton = _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#sendReportReason'
  );
  sendButton.addEventListener('click', sendForm);

  const closeReportReasonSentInfo = _reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"].querySelector(
    '#closeReportReasonSentInfo'
  );
  closeReportReasonSentInfo.addEventListener('click', hideReportReasonPopup);
}

function initPopupContainer() {
  const popupContainer = document.querySelector('.qa-body-wrapper');
  popupContainer.appendChild(_reportReasonPopupCreator__WEBPACK_IMPORTED_MODULE_1__["default"]);
}

function sendForm(event) {
  event.preventDefault();

  const sendButton = event.target;
  sendButton.blur();

  const isFormValid = validateForm(sendButton);
  if (!isFormValid) {
    return;
  }

  toggleSendWaitingState(sendButton, true);
  Object(_ajaxService__WEBPACK_IMPORTED_MODULE_0__["default"])(
    reportReasonPopupForm.action,
    prepareFormData(),
    responseWaitTimeoutMs
  ).then(
      () => onAjaxSuccess(sendButton),
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
  const formData = new FormData(reportReasonPopupForm);
  const reportReasons = formData.getAll('reportReason');

  // Avoid form data duplication, because of <textarea>, which can has custom reason with the same [name] attribute
  const valueIndex = Number(reportReasons[0] === 'custom');
  formData.set('reportReason', reportReasons[valueIndex]);
  formData.set('questionId', reportMetaData.rootId);

  if (reportMetaData.answerId) {
    formData.set('answerId', reportMetaData.answerId);
  }
  if (reportMetaData.commentId) {
    formData.set('commentId', reportMetaData.commentId);
  }

  return formData;
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

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);


/***/ }),

/***/ "./src/helpers/reportReasonPopupCreator.js":
/*!*************************************************!*\
  !*** ./src/helpers/reportReasonPopupCreator.js ***!
  \*************************************************/
/*! exports provided: reportReasonPopupDOMReferences, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reportReasonPopupDOMReferences", function() { return reportReasonPopupDOMReferences; });
/* harmony import */ var _data_reasonsCollection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../data/reasonsCollection */ "./src/data/reasonsCollection.json");
var _data_reasonsCollection__WEBPACK_IMPORTED_MODULE_0___namespace = /*#__PURE__*/__webpack_require__.t(/*! ../data/reasonsCollection */ "./src/data/reasonsCollection.json", 1);


const listItemsDOM = _data_reasonsCollection__WEBPACK_IMPORTED_MODULE_0__.reduce(
  (listItems, reason, index, reasonsCollection) => {
    const reasonItemId = `reportReasonItem${index}`;
    const isLast = index === reasonsCollection.length - 1;
    const textAreaDOM =
      isLast &&
      `<textarea id="customReportReason" rows="3" cols="47" name="reportReason" class="report-reason-popup__custom-report-reason" data-requirable="true"></textarea>`;

    return (
      listItems +
      `
        <li>
            <label for="${reasonItemId}">
                <input id="${reasonItemId}" 
                        type="radio" 
                        value="${reason.value}" 
                        name="reportReason" 
                        data-requirable="true">
                ${reason.description}
            </label>
            ${isLast ? textAreaDOM : ''}
        </li>
    `
    );
  },
  ''
);

const reportReasonPopupDOMWrapper = (function createReportReasonPopupWrapper() {
  const popupWrapper = document.createElement('div');
  popupWrapper.classList.add('report-reason-wrapper');
  popupWrapper.innerHTML = `
        <link href="../qa-plugin/report-reason/style.css" rel="stylesheet" type="text/css">
         
        <div id="reportReasonPopup" class="report-reason-popup">
            <p>Zaznacz proszę powód zgłoszenia lub podaj własny:</p>
            
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

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_reportReasonPopupController__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/reportReasonPopupController */ "./src/helpers/reportReasonPopupController.js");


document.addEventListener(
  'DOMContentLoaded',
  function initReportReasonPlugin() {
    Object(_helpers_reportReasonPopupController__WEBPACK_IMPORTED_MODULE_0__["default"])();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener(
      'click',
      _helpers_reportReasonPopupController__WEBPACK_IMPORTED_MODULE_0__["default"].handler,
      true
    );
  }
);


/***/ })

/******/ });
//# sourceMappingURL=script.js.map