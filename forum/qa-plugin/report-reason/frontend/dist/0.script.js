(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

module.exports = _arrayWithHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}

module.exports = _arrayWithoutHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter);
}

module.exports = _iterableToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArrayLimit(arr, i) {
  if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return;
  var _arr = [];
  var _n = true;
  var _d = false;
  var _e = undefined;

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

module.exports = _iterableToArrayLimit;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableRest;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableSpread;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js");

var iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ "./node_modules/@babel/runtime/helpers/nonIterableRest.js");

function _slicedToArray(arr, i) {
  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();
}

module.exports = _slicedToArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray;

/***/ }),

/***/ "./src/ajaxService.js":
/*!****************************!*\
  !*** ./src/ajaxService.js ***!
  \****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ "./node_modules/@babel/runtime/helpers/asyncToGenerator.js");
/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);


var AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
var TIMEOUT = 5000;
var URL = '/report-flag';
var CONTENT_TYPE = 'application/json';

var sendAjax = function sendAjax(data) {
  return new Promise(function (resolve, reject) {
    var timeoutId = setTimeout(function () {
      reject(AJAX_TIMEOUT_REASON);
    }, TIMEOUT);
    fetch(URL, {
      method: 'POST',
      headers: {
        'Content-Type': CONTENT_TYPE
      },
      body: JSON.stringify(data)
    }).then( /*#__PURE__*/function () {
      var _ref = _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(value) {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                clearTimeout(timeoutId); // let text = null;
                // if (purpose === AJAX_PURPOSE.UN_FLAG) {
                //   try {
                //     text = await value.text();
                //   } catch ( e ) {
                //     console.error( 'value.text not worked... /e: ', e );
                //   }
                // }

                console.warn('fetch response: ', value
                /*, ' /?:', text*/
                ); // const resolveValue = purpose === AJAX_PURPOSE.FLAG ? value.json() : 'ok';

                resolve(value.json());

              case 3:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }));

      return function (_x) {
        return _ref.apply(this, arguments);
      };
    }());
  });
};

/* harmony default export */ __webpack_exports__["default"] = (sendAjax);

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
var _FLAG_REASONS_METADAT = FLAG_REASONS_METADATA,
    NOTICE_LENGTH = _FLAG_REASONS_METADAT.NOTICE_LENGTH,
    POPUP_LABELS = _FLAG_REASONS_METADAT.POPUP_LABELS;
var swapElement = function swapElement(referenceNode, html) {
  var tmpParent = document.createElement('div');
  tmpParent.innerHTML = html;
  var newElement = tmpParent.removeChild(tmpParent.firstElementChild);
  referenceNode.parentNode.insertBefore(newElement, referenceNode);
  referenceNode.remove(); // return newElement;
};
var elementsHTMLMap = new Map([['textarea', "<textarea id=\"customReportReason\"\n        class=\"report-reason-popup__custom-report-reason\"\n        name=\"reportReason\"\n        data-requirable=\"true\"\n        maxlength=\"".concat(NOTICE_LENGTH, "\"\n        rows=\"3\"\n        cols=\"47\"></textarea>")], ['getListItem', function (_ref) {
  var reasonKey = _ref.reasonKey,
      reasonValue = _ref.reasonValue,
      index = _ref.index,
      isLast = _ref.isLast,
      textAreaDOM = _ref.textAreaDOM;
  return "\n            <!-- TODO: handle checking inputs while tabbing -->\n            <li tabindex=\"1\">\n                <label for=\"".concat(reasonKey, "\">\n                    <input id=\"").concat(reasonKey, "\" \n                            type=\"radio\" \n                            value=\"").concat(index, "\" \n                            name=\"reportReason\" \n                            data-requirable=\"true\">\n                    ").concat(reasonValue, "\n                </label>\n                ").concat(isLast ? textAreaDOM : '', "\n            </li>\n        ");
}], ['getPopupWrapper', function (listItemsDOM) {
  return "\n            <div id=\"reportReasonPopup\" class=\"report-reason-popup\">\n                <p>".concat(POPUP_LABELS.HEADER, "</p>\n                \n                <form method=\"post\" class=\"report-reason-popup__form\">\n                    <ul id=\"reportReasonList\" class=\"report-reason-popup__list\">").concat(listItemsDOM, "</ul>\n                \n                    <p id=\"reportReasonValidationError\" class=\"report-reason-popup__validation-error\">").concat(POPUP_LABELS.NO_REASON_CHECKED, "</p>\n                    \n                    <!-- TODO: why its input not button? -->\n                    <input id=\"cancelReportReason\" type=\"button\" value=\"").concat(POPUP_LABELS.CANCEL, "\" class=\"report-reason-popup__button report-reason-popup__button--cancel\">\n                    <button id=\"sendReportReason\" type=\"submit\" class=\"report-reason-popup__button report-reason-popup__button--save\">").concat(POPUP_LABELS.SEND, "</button>\n                </form>\n            </div>\n            <div id=\"reportReasonSuccessInfo\" class=\"report-reason-popup__success-info\">\n                ").concat(POPUP_LABELS.REPORT_SENT, "\n                <button id=\"closeReportReasonSentInfo\" class=\"report-reason-popup__button report-reason-popup__button--close\" type=\"button\">").concat(POPUP_LABELS.CLOSE, "</button>\n            </div>");
}]]);

/***/ }),

/***/ "./src/popupController.js":
/*!********************************!*\
  !*** ./src/popupController.js ***!
  \********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _ajaxService__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ajaxService */ "./src/ajaxService.js");
/* harmony import */ var _popupFactory__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./popupFactory */ "./src/popupFactory.js");
/* harmony import */ var _unFlagButton__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./unFlagButton */ "./src/unFlagButton.js");
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./misc */ "./src/misc.js");




function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }





console.warn('reportReasonPopupDOMWrapper: ', _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"]);
var reportReasonPopup = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].reportReasonPopup,
    reportReasonPopupForm = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].reportReasonPopupForm,
    customReportReason = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].customReportReason,
    reportReasonSuccessInfo = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].reportReasonSuccessInfo,
    requirableFormElements = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].requirableFormElements,
    reportReasonValidationError = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMReferences"].reportReasonValidationError;
var questionViewMeta = document.querySelector('.qa-q-view-meta');
var BTN_NAME_SUFFIXES_REGEX = /do(clear|un)?flag[s]?/;
var FLAG_BTN_NAME_SUFFIX = 'doflag'; // const doCommentInputNameSuffix = '_docomment';

var reportFlagMap = {
  regex: {
    question: /q_doflag/,
    answer: /^a(\d+)_doflag/,
    comment: /^c(\d+)_doflag/,
    doComment: /^a(\d+)_docomment/
  },
  getPostIdFromInputName: function getPostIdFromInputName(postType, inputName) {
    // TODO: check if it works (changed exec to match)...
    var _inputName$match = inputName.match(this.regex[postType]),
        _inputName$match2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_inputName$match, 2),
        postId = _inputName$match2[1];

    return postId;
  },
  recognizeInputKindByName: function recognizeInputKindByName(inputName) {
    var _Object$entries$find = Object.entries(this.regex).find(function (_ref) {
      var _ref2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_ref, 2),
          regexKey = _ref2[0],
          regexValue = _ref2[1];

      return regexValue.test(inputName);
    }),
        _Object$entries$find2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_Object$entries$find, 1),
        mappedInputNameRegexKey = _Object$entries$find2[0];

    return mappedInputNameRegexKey;
  },
  collectForumPostMetaData: function collectForumPostMetaData() {
    var postType = this.recognizeInputKindByName(flagButtonDOM.name);
    var postRootSource = flagButtonDOM.form.getAttribute('action');
    var postMetaData = {
      questionId: postRootSource.split('/')[1],
      postType: postType.slice(0, 1)
    };
    postMetaData.postId = this.getPostIdFromInputName(postType, flagButtonDOM.name) || postMetaData.questionId; // if (postType === 'answer') {
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
  }
};
var bootstrapUsed = false;
var flagButtonDOM = null;

var showReportReasonPopup = function showReportReasonPopup(originalFormActionAttribute) {
  reportReasonPopupForm.action = originalFormActionAttribute;
  _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].classList.add('report-reason-wrapper--show');
};

var hideReportReasonPopup = function hideReportReasonPopup() {
  reportReasonSuccessInfo.classList.remove('report-reason-popup__success-info--show');
  _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].classList.remove('report-reason-wrapper--show');
  customReportReason.classList.remove('report-reason-popup__custom-report-reason--show');
  reportReasonPopup.classList.remove('report-reason-popup--hide');
  reportReasonValidationError.classList.remove('report-reason-popup__validation-error--show');
  reportReasonPopupForm.reset();
};

var bootstrapReportReasonPopup = function bootstrapReportReasonPopup() {
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
  _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].addEventListener('click', function (event) {
    var checkDOMElementsId = function checkDOMElementsId(DOMElement) {
      return DOMElement.id === 'reportReasonPopup' || DOMElement.id === 'reportReasonSuccessInfo';
    };

    var clickedOutsidePopup = !event.composedPath().some(checkDOMElementsId);

    if (clickedOutsidePopup) {
      hideReportReasonPopup();
    }
  });
}

function initReasonList() {
  var reasonList = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].querySelector('#reportReasonList');
  reasonList.addEventListener('change', function (_ref3) {
    var target = _ref3.target;
    reportReasonValidationError.classList.remove('report-reason-popup__validation-error--show');

    if (reasonList.children[reasonList.children.length - 1].contains(target)) {
      customReportReason.classList.add('report-reason-popup__custom-report-reason--show');
      setTimeout(customReportReason.focus.bind(customReportReason));
    } else {
      customReportReason.classList.remove('report-reason-popup__custom-report-reason--show');
    }
  });
}

function initButtons() {
  var cancelButton = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].querySelector('#cancelReportReason');
  cancelButton.addEventListener('click', hideReportReasonPopup);
  var sendButton = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].querySelector('#sendReportReason');
  sendButton.addEventListener('click', submitForm);
  var closeReportReasonSentInfo = _popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"].querySelector('#closeReportReasonSentInfo');
  closeReportReasonSentInfo.addEventListener('click', hideReportReasonPopup);
}

function initPopupContainer() {
  var popupContainer = document.querySelector('.qa-body-wrapper');
  popupContainer.appendChild(_popupFactory__WEBPACK_IMPORTED_MODULE_4__["reportReasonPopupDOMWrapper"]);
}

function submitForm(event) {
  event.preventDefault();
  var sendButton = event.target;
  sendButton.blur();
  var isFormValid = validateForm(sendButton);

  if (!isFormValid) {
    return;
  }

  toggleSendWaitingState(sendButton, true);
  var formData = prepareFormData();
  Object(_ajaxService__WEBPACK_IMPORTED_MODULE_3__["default"])(formData).then(function (response) {
    console.warn('response:', response);
    onAjaxSuccess(response, formData, sendButton);
  }, function (ajaxError) {
    return onAjaxError(sendButton, ajaxError);
  });
}

function onAjaxSuccess(response, formData, sendButton) {
  toggleSendWaitingState(sendButton, false);
  updateCurrentPostFlags(response.currentFlags, formData);
  Object(_misc__WEBPACK_IMPORTED_MODULE_6__["swapElement"])(flagButtonDOM, Object(_unFlagButton__WEBPACK_IMPORTED_MODULE_5__["default"])({
    postType: formData.postType,
    questionId: formData.questionId,
    postId: formData.postId,
    parentId: getPostParentId()
  }));
  showSuccessPopup();
}

function onAjaxError(sendButton, ajaxError) {
  toggleSendWaitingState(sendButton, false); // TODO: add proper error handling

  console.warn('ajaxError:', ajaxError);
}

function validateForm(sendButton) {
  var isAnyFormElementUsed = _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(requirableFormElements).some(function (element) {
    var isCheckedRadioInput = element.type === 'radio' && element.value !== 'custom' && element.checked;
    var isFilledTextArea = element.tagName.toLowerCase() === 'textarea' && element.value;
    return isCheckedRadioInput || isFilledTextArea;
  });

  if (!isAnyFormElementUsed) {
    notifyAboutValidationError(sendButton);
  }

  return isAnyFormElementUsed;
}

function notifyAboutValidationError(sendButton) {
  reportReasonValidationError.classList.add('report-reason-popup__validation-error--show');
  sendButton.classList.add('report-reason-popup__button--save--validation-blink');
  setTimeout(function () {
    sendButton.classList.remove('report-reason-popup__button--save--validation-blink');
  }, 1000);
}

function prepareFormData() {
  var reportMetaData = reportFlagMap.collectForumPostMetaData();

  var _FormData$getAll = new FormData(reportReasonPopupForm).getAll('reportReason'),
      _FormData$getAll2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_FormData$getAll, 2),
      reasonId = _FormData$getAll2[0],
      notice = _FormData$getAll2[1];

  return _objectSpread(_objectSpread({}, reportMetaData), {}, {
    reasonId: reasonId,
    notice: notice,
    reportType: 'addFlag'
  });
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

function updateCurrentPostFlags(currentFlagsHTML, _ref4) {
  var postType = _ref4.postType,
      postId = _ref4.postId;
  var flagsMetadataWrapper = postType === 'q' ? questionViewMeta : document.querySelector("#".concat(postType).concat(postId, " .qa-").concat(postType, "-item-meta"));
  var targetElementSelector = ".qa-".concat(postType, "-item-flags");
  var targetElement = flagsMetadataWrapper.querySelector(targetElementSelector);

  if (targetElement) {
    /*swapElement(targetElement, currentFlagsHTML);*/
    targetElement.outerHTML = currentFlagsHTML;
  } else {
    var responseAsDOM = new DOMParser().parseFromString(currentFlagsHTML, 'text/html').querySelector(targetElementSelector);
    flagsMetadataWrapper.appendChild(responseAsDOM);
  }
}

function showSuccessPopup() {
  reportReasonPopup.classList.add('report-reason-popup--hide');
  reportReasonSuccessInfo.classList.add('report-reason-popup', 'report-reason-popup__success-info--show');
}

function getPostParentId() {
  var parentElement = flagButtonDOM.closest('[id*="_list"]');

  if (!parentElement) {
    return null;
  }

  return parentElement.id.slice(1, parentElement.id.indexOf('_'));
}

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);

/***/ }),

/***/ "./src/popupFactory.js":
/*!*****************************!*\
  !*** ./src/popupFactory.js ***!
  \*****************************/
/*! exports provided: reportReasonPopupDOMReferences, reportReasonPopupDOMWrapper */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reportReasonPopupDOMReferences", function() { return reportReasonPopupDOMReferences; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "reportReasonPopupDOMWrapper", function() { return reportReasonPopupDOMWrapper; });
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./misc */ "./src/misc.js");



var reportReasonPopupDOMWrapper = function () {
  var listItemsDOM = Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce(function (listItems, _ref, index, flagReasonsCollection) {
    var _ref2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_ref, 2),
        reasonKey = _ref2[0],
        reasonValue = _ref2[1];

    // const reasonItemId = `reportReasonItem${index}`;
    var isLast = index === flagReasonsCollection.length - 1;
    var textAreaDOM = isLast && _misc__WEBPACK_IMPORTED_MODULE_1__["elementsHTMLMap"].get('textarea');
    return listItems + _misc__WEBPACK_IMPORTED_MODULE_1__["elementsHTMLMap"].get('getListItem')({
      reasonKey: reasonKey,
      reasonValue: reasonValue,
      index: index,
      isLast: isLast,
      textAreaDOM: textAreaDOM
    });
  }, '');
  var popupWrapper = document.createElement('div');
  popupWrapper.classList.add('report-reason-wrapper');
  popupWrapper.innerHTML = _misc__WEBPACK_IMPORTED_MODULE_1__["elementsHTMLMap"].get('getPopupWrapper')(listItemsDOM);
  return popupWrapper;
}();

var reportReasonPopupDOMReferences = {
  reportReasonPopup: reportReasonPopupDOMWrapper.querySelector('#reportReasonPopup'),
  reportReasonPopupForm: reportReasonPopupDOMWrapper.querySelector('form'),
  customReportReason: reportReasonPopupDOMWrapper.querySelector('#customReportReason'),
  reportReasonSuccessInfo: reportReasonPopupDOMWrapper.querySelector('#reportReasonSuccessInfo'),
  requirableFormElements: reportReasonPopupDOMWrapper.querySelectorAll('[data-requirable="true"]'),
  reportReasonValidationError: reportReasonPopupDOMWrapper.querySelector('#reportReasonValidationError')
};


/***/ }),

/***/ "./src/unFlagButton.js":
/*!*****************************!*\
  !*** ./src/unFlagButton.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function getUnFlagButtonHTML(_ref) {
  var postType = _ref.postType,
      questionId = _ref.questionId,
      postId = _ref.postId,
      parentId = _ref.parentId;
  var namePrefix = postType + postId;
  var onclick = '';
  var nameSuffix = '_dounflag';
  var value = 'wycofaj zgłoszenie';
  var title = 'Wycofaj zgłoszenie tej treści';
  var type = 'submit';
  var clazz = 'qa-form-light-button qa-form-light-button-unflag';

  switch (postType) {
    case 'q':
      {
        onclick = 'qa_show_waiting_after(this, false)';
        namePrefix = postType;
        break;
      }

    case 'a':
      {
        onclick = "return qa_answer_click(".concat(postId, ", ").concat(questionId, ", this);");
        break;
      }

    case 'c':
      {
        onclick = "return qa_comment_click(".concat(postId, ", ").concat(questionId, ", ").concat(parentId, ", this);");
        break;
      }

    default:
      {
        throw new Error("Unrecognized postType: ".concat(postType, " for questionId: ").concat(questionId, " and postId: ").concat(postId));
      }
  }

  return "\n\t\t<input name=\"".concat(namePrefix).concat(nameSuffix, "\" \n\t\t\tonclick=\"").concat(onclick, "\"\n\t\t\tvalue=\"").concat(value, "\"\n\t\t\ttitle=\"").concat(title, "\"\n\t\t\ttype=\"").concat(type, "\" \n\t\t\tclass=\"").concat(clazz, "\">\n\t");
}

/* harmony default export */ __webpack_exports__["default"] = (getUnFlagButtonHTML);

/***/ })

}]);
//# sourceMappingURL=0.script.js.map