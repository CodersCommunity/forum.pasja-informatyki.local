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

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

module.exports = _classCallCheck;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

module.exports = _createClass;

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

/***/ "./src/bootstrap.js":
/*!**************************!*\
  !*** ./src/bootstrap.js ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _popupFactory__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./popupFactory */ "./src/popupFactory.js");
/* harmony import */ var _flagController__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./flagController */ "./src/flagController.js");
/* harmony import */ var _formController__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./formController */ "./src/formController.js");




var bootstrapReportReasonPopup = function bootstrapReportReasonPopup() {
  var _init = Object(_popupFactory__WEBPACK_IMPORTED_MODULE_0__["default"])(),
      reportReasonPopupDOMReferences = _init.reportReasonPopupDOMReferences,
      showReportReasonPopup = _init.showReportReasonPopup;

  var flagController = new _flagController__WEBPACK_IMPORTED_MODULE_1__["default"](reportReasonPopupDOMReferences.reportReasonPopupForm, showReportReasonPopup);
  return flagController.onClick.bind(flagController);
};

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);

/***/ }),

/***/ "./src/flagController.js":
/*!*******************************!*\
  !*** ./src/flagController.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _unFlagButton__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./unFlagButton */ "./src/unFlagButton.js");
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./misc */ "./src/misc.js");





var FLAG_BTN_NAME_SUFFIX = 'doflag';

var FlagController = /*#__PURE__*/function () {
  function FlagController(reportReasonPopupForm, showReportReasonPopup) {
    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, FlagController);

    this.reportReasonPopupForm = reportReasonPopupForm;
    this.showReportReasonPopup = showReportReasonPopup;
    this.flagButtonDOM = null;
    this.regex = {
      question: /q_doflag/,
      answer: /^a(\d+)_doflag/,
      comment: /^c(\d+)_doflag/,
      doComment: /^a(\d+)_docomment/
    };
  }

  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(FlagController, [{
    key: "onClick",
    value: function onClick(event) {
      if (event.target.name && event.target.name.endsWith(FLAG_BTN_NAME_SUFFIX)) {
        event.preventDefault();
        event.stopPropagation();
        this.flagButtonDOM = event.target;
        this.reportReasonPopupForm = event.target.form.action;
        this.showReportReasonPopup();
      }
    }
  }, {
    key: "getPostIdFromInputName",
    value: function getPostIdFromInputName(postType, inputName) {
      // TODO: check if it works (changed exec to match)...
      var _inputName$match = inputName.match(this.regex[postType]),
          _inputName$match2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_inputName$match, 2),
          postId = _inputName$match2[1];

      return postId;
    }
  }, {
    key: "recognizeInputKindByName",
    value: function recognizeInputKindByName(inputName) {
      var _Object$entries$find = Object.entries(this.regex).find(function (_ref) {
        var _ref2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_ref, 2),
            regexKey = _ref2[0],
            regexValue = _ref2[1];

        return regexValue.test(inputName);
      }),
          _Object$entries$find2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_Object$entries$find, 1),
          mappedInputNameRegexKey = _Object$entries$find2[0];

      return mappedInputNameRegexKey;
    }
  }, {
    key: "collectForumPostMetaData",
    value: function collectForumPostMetaData() {
      var postType = this.recognizeInputKindByName(this.flagButtonDOM.name);
      var postRootSource = this.flagButtonDOM.form.getAttribute('action');
      var postMetaData = {
        questionId: postRootSource.split('/')[1],
        postType: postType.slice(0, 1)
      };
      postMetaData.postId = this.getPostIdFromInputName(postType, this.flagButtonDOM.name) || postMetaData.questionId;
      return postMetaData;
    }
  }]);

  return FlagController;
}();

var reportReasonPopupDOMWrapper = null;
var reportReasonPopupDOMReferences = null; // console.warn('reportReasonPopupDOMWrapper: ', reportReasonPopupDOMWrapper);
// const {
// 	// reportReasonPopup,
// 	// reportReasonPopupForm,
// 	reportReasonSuccessInfo,
// 	reportReasonValidationError,
// } = reportReasonPopupDOMReferences;

var questionViewMeta = document.querySelector('.qa-q-view-meta');
var BTN_NAME_SUFFIXES_REGEX = /do(clear|un)?flag[s]?/; // const doCommentInputNameSuffix = '_docomment';

var reportFlagMap = {};

function onAjaxSuccess(response, formData, sendButton) {
  toggleSendWaitingState(sendButton, false);
  Object(_misc__WEBPACK_IMPORTED_MODULE_4__["updateCurrentPostFlags"])(response.currentFlags, formData);
  Object(_misc__WEBPACK_IMPORTED_MODULE_4__["swapElement"])(flagButtonDOM, Object(_unFlagButton__WEBPACK_IMPORTED_MODULE_3__["default"])({
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

function notifyAboutValidationError(sendButton) {
  reportReasonValidationError.classList.add('report-reason-popup__validation-error--show');
  sendButton.classList.add('report-reason-popup__button--save--validation-blink');
  setTimeout(function () {
    sendButton.classList.remove('report-reason-popup__button--save--validation-blink');
  }, 1000);
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

function showSuccessPopup() {
  reportReasonPopup.classList.add('report-reason-popup--hide');
  reportReasonSuccessInfo.classList.add('report-reason-popup', 'report-reason-popup__success-info--show');
}

function getPostParentId(flagButtonDOM) {
  var parentElement = flagButtonDOM.closest('[id*="_list"]');

  if (!parentElement) {
    return null;
  }

  return parentElement.id.slice(1, parentElement.id.indexOf('_'));
} // export { handleFlagClick };


/* harmony default export */ __webpack_exports__["default"] = (FlagController);

/***/ }),

/***/ "./src/formController.js":
/*!*******************************!*\
  !*** ./src/formController.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4__);






function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

// import sendAjax from "./ajaxService";
// import { reportReasonPopupDOMReferences } from './popupFactory';
var FormController = /*#__PURE__*/function () {
  function FormController(reportReasonPopupForm) {
    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3___default()(this, FormController);

    this.reportReasonPopupForm = reportReasonPopupForm;
  }

  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4___default()(FormController, [{
    key: "validateForm",
    value: function validateForm(reportReasonPopupDOMReferences, sendButton) {
      var isAnyFormElementUsed = _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(reportReasonPopupDOMReferences.requirableFormElements).some(function (element) {
        var isCheckedRadioInput = element.type === 'radio' && element.value !== 'custom' && element.checked;
        var isFilledTextArea = element.tagName.toLowerCase() === 'textarea' && element.value;
        return isCheckedRadioInput || isFilledTextArea;
      });

      if (!isAnyFormElementUsed) {
        notifyAboutValidationError(sendButton);
      }

      return isAnyFormElementUsed;
    }
  }, {
    key: "prepareFormData",
    value: function prepareFormData(collectedForumPostMetaData) {
      var _FormData$getAll = new FormData(this.reportReasonPopupForm).getAll('reportReason'),
          _FormData$getAll2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1___default()(_FormData$getAll, 2),
          reasonId = _FormData$getAll2[0],
          notice = _FormData$getAll2[1];

      return _objectSpread(_objectSpread({}, collectedForumPostMetaData), {}, {
        reasonId: reasonId,
        notice: notice,
        reportType: 'addFlag'
      });
    }
  }, {
    key: "submitForm",
    value: function submitForm(event, collectedForumPostMetaData) {
      event.preventDefault();
      var sendButton = event.target;
      sendButton.blur();
      var isFormValid = this.validateForm(sendButton);

      if (!isFormValid) {
        return;
      }

      toggleSendWaitingState(sendButton, true);
      var formData = this.prepareFormData(collectedForumPostMetaData);
      sendAjax(formData).then(function (response) {
        console.warn('response:', response);
        onAjaxSuccess(response, formData, sendButton);
      }, function (ajaxError) {
        return onAjaxError(sendButton, ajaxError);
      });
    }
  }]);

  return FormController;
}();

/* harmony default export */ __webpack_exports__["default"] = (FormController);

/***/ }),

/***/ "./src/misc.js":
/*!*********************!*\
  !*** ./src/misc.js ***!
  \*********************/
/*! exports provided: swapElement, elementsHTMLMap, updateCurrentPostFlags */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "swapElement", function() { return swapElement; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "elementsHTMLMap", function() { return elementsHTMLMap; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateCurrentPostFlags", function() { return updateCurrentPostFlags; });
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

var elementsHTMLMap = new Map([['textarea', "<textarea id=\"customReportReason\"\n\t\t\tclass=\"report-reason-popup__custom-report-reason\"\n\t\t\tname=\"reportReason\"\n\t\t\tdata-requirable=\"true\"\n\t\t\tmaxlength=\"".concat(NOTICE_LENGTH, "\"\n\t\t\trows=\"3\"\n\t\t\tcols=\"47\"></textarea>")], ['getListItem', function (_ref) {
  var reasonKey = _ref.reasonKey,
      reasonValue = _ref.reasonValue,
      index = _ref.index,
      isLast = _ref.isLast,
      textAreaDOM = _ref.textAreaDOM;
  return "\n\t\t\t\t<!-- TODO: handle checking inputs while tabbing -->\n\t\t\t\t<li tabindex=\"1\">\n\t\t\t\t\t<label for=\"".concat(reasonKey, "\">\n\t\t\t\t\t\t<input id=\"").concat(reasonKey, "\" \n\t\t\t\t\t\t\t\ttype=\"radio\" \n\t\t\t\t\t\t\t\tvalue=\"").concat(index, "\" \n\t\t\t\t\t\t\t\tname=\"reportReason\" \n\t\t\t\t\t\t\t\tdata-requirable=\"true\">\n\t\t\t\t\t\t").concat(reasonValue, "\n\t\t\t\t\t</label>\n\t\t\t\t\t").concat(isLast ? textAreaDOM : '', "\n\t\t\t\t</li>\n\t\t\t");
}], ['getPopupWrapper', function (listItemsDOM) {
  return "\n\t\t\t\t<div id=\"reportReasonPopup\" class=\"report-reason-popup\">\n\t\t\t\t\t<p>".concat(POPUP_LABELS.HEADER, "</p>\n\t\t\t\t\t\n\t\t\t\t\t<form method=\"post\" class=\"report-reason-popup__form\">\n\t\t\t\t\t\t<ul id=\"reportReasonList\" class=\"report-reason-popup__list\">").concat(listItemsDOM, "</ul>\n\t\t\t\t\t\n\t\t\t\t\t\t<p id=\"reportReasonValidationError\" class=\"report-reason-popup__validation-error\">").concat(POPUP_LABELS.NO_REASON_CHECKED, "</p>\n\t\t\t\t\t\t\n\t\t\t\t\t\t<!-- TODO: why its input not button? -->\n\t\t\t\t\t\t<input id=\"cancelReportReason\" type=\"button\" value=\"").concat(POPUP_LABELS.CANCEL, "\" class=\"report-reason-popup__button report-reason-popup__button--cancel\">\n\t\t\t\t\t\t<button id=\"sendReportReason\" type=\"submit\" class=\"report-reason-popup__button report-reason-popup__button--save\">").concat(POPUP_LABELS.SEND, "</button>\n\t\t\t\t\t</form>\n\t\t\t\t</div>\n\t\t\t\t<div id=\"reportReasonSuccessInfo\" class=\"report-reason-popup__success-info\">\n\t\t\t\t\t").concat(POPUP_LABELS.REPORT_SENT, "\n\t\t\t\t\t<button id=\"closeReportReasonSentInfo\" class=\"report-reason-popup__button report-reason-popup__button--close\" type=\"button\">").concat(POPUP_LABELS.CLOSE, "</button>\n\t\t\t\t</div>");
}]]);

function updateCurrentPostFlags(currentFlagsHTML, _ref2) {
  var postType = _ref2.postType,
      postId = _ref2.postId;
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



/***/ }),

/***/ "./src/popupFactory.js":
/*!*****************************!*\
  !*** ./src/popupFactory.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _misc__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./misc */ "./src/misc.js");
/* harmony import */ var _formController__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./formController */ "./src/formController.js");
/* harmony import */ var _flagController__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./flagController */ "./src/flagController.js");




 // import submitForm from "./formController";



var PopupFactory = /*#__PURE__*/function () {
  function PopupFactory(submitForm) {
    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, PopupFactory);

    this.reportReasonPopupDOMWrapper = null;
    this.reportReasonPopupDOMReferences = null;
    this.submitForm = submitForm;
    this.initReportReasonPopupDOMWrapper();
    this.initOffClickHandler();
    this.initReasonList();
    this.initButtons();
    this.initPopupContainer();
    this.initPopupDOMReferences();
  }

  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(PopupFactory, [{
    key: "initOffClickHandler",
    value: function initOffClickHandler() {
      var _this = this;

      this.reportReasonPopupDOMWrapper.addEventListener('click', function (event) {
        var checkDOMElementsId = function checkDOMElementsId(DOMElement) {
          return DOMElement.id === 'reportReasonPopup' || DOMElement.id === 'reportReasonSuccessInfo';
        };

        var clickedOutsidePopup = !event.composedPath().some(checkDOMElementsId);

        if (clickedOutsidePopup) {
          _this.hideReportReasonPopup();
        }
      });
    }
  }, {
    key: "initReasonList",
    value: function initReasonList() {
      var _this2 = this;

      var reasonList = this.reportReasonPopupDOMWrapper.querySelector('#reportReasonList');
      reasonList.addEventListener('change', function (_ref) {
        var target = _ref.target;

        _this2.reportReasonPopupDOMReferences.reportReasonValidationError.classList.remove('report-reason-popup__validation-error--show');

        if (reasonList.children[reasonList.children.length - 1].contains(target)) {
          _this2.reportReasonPopupDOMReferences.customReportReason.classList.add('report-reason-popup__custom-report-reason--show');

          setTimeout(_this2.reportReasonPopupDOMReferences.customReportReason.focus.bind(_this2.reportReasonPopupDOMReferences.customReportReason));
        } else {
          _this2.reportReasonPopupDOMReferences.customReportReason.classList.remove('report-reason-popup__custom-report-reason--show');
        }
      });
    }
  }, {
    key: "initButtons",
    value: function initButtons() {
      var _this3 = this;

      var cancelButton = this.reportReasonPopupDOMWrapper.querySelector('#cancelReportReason');
      cancelButton.addEventListener('click', this.hideReportReasonPopup);
      var sendButton = this.reportReasonPopupDOMWrapper.querySelector('#sendReportReason');
      sendButton.addEventListener('click', function (event) {
        _this3.submitForm(event, _this3.collectForumPostMetaData());
      });
      var closeReportReasonSentInfo = this.reportReasonPopupDOMWrapper.querySelector('#closeReportReasonSentInfo');
      closeReportReasonSentInfo.addEventListener('click', this.hideReportReasonPopup);
    }
  }, {
    key: "initPopupContainer",
    value: function initPopupContainer() {
      var popupContainer = document.querySelector('.qa-body-wrapper');
      popupContainer.appendChild(this.reportReasonPopupDOMWrapper);
    }
  }, {
    key: "initReportReasonPopupDOMWrapper",
    value: function initReportReasonPopupDOMWrapper() {
      var listItemsDOM = Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce(function (listItems, _ref2, index, flagReasonsCollection) {
        var _ref3 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_ref2, 2),
            reasonKey = _ref3[0],
            reasonValue = _ref3[1];

        var isLast = index === flagReasonsCollection.length - 1;
        var textAreaDOM = isLast && _misc__WEBPACK_IMPORTED_MODULE_3__["elementsHTMLMap"].get('textarea');
        return listItems + _misc__WEBPACK_IMPORTED_MODULE_3__["elementsHTMLMap"].get('getListItem')({
          reasonKey: reasonKey,
          reasonValue: reasonValue,
          index: index,
          isLast: isLast,
          textAreaDOM: textAreaDOM
        });
      }, '');
      var popupWrapper = document.createElement('div');
      popupWrapper.classList.add('report-reason-wrapper');
      popupWrapper.innerHTML = _misc__WEBPACK_IMPORTED_MODULE_3__["elementsHTMLMap"].get('getPopupWrapper')(listItemsDOM);
      this.reportReasonPopupDOMWrapper = popupWrapper;
    }
  }, {
    key: "initPopupDOMReferences",
    value: function initPopupDOMReferences() {
      this.reportReasonPopupDOMReferences = {
        reportReasonPopup: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonPopup'),
        reportReasonPopupForm: this.reportReasonPopupDOMWrapper.querySelector('form'),
        customReportReason: this.reportReasonPopupDOMWrapper.querySelector('#customReportReason'),
        reportReasonSuccessInfo: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonSuccessInfo'),
        requirableFormElements: this.reportReasonPopupDOMWrapper.querySelectorAll('[data-requirable="true"]'),
        reportReasonValidationError: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonValidationError')
      };
    }
  }, {
    key: "showReportReasonPopup",
    value: function showReportReasonPopup() {
      this.reportReasonPopupDOMWrapper.classList.add('report-reason-wrapper--show');
    }
  }, {
    key: "hideReportReasonPopup",
    value: function hideReportReasonPopup() {
      this.reportReasonPopupDOMReferences.reportReasonSuccessInfo.classList.remove('report-reason-popup__success-info--show');
      this.reportReasonPopupDOMWrapper.classList.remove('report-reason-wrapper--show');
      this.reportReasonPopupDOMReferences.customReportReason.classList.remove('report-reason-popup__custom-report-reason--show');
      this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove('report-reason-popup--hide');
      this.reportReasonPopupDOMReferences.reportReasonValidationError.classList.remove('report-reason-popup__validation-error--show');
      this.reportReasonPopupDOMReferences.reportReasonPopupForm.reset();
    } // getReportReasonPopupDOMWrapper() {
    // 	return this.reportReasonPopupDOMWrapper;
    // }

  }, {
    key: "getReportReasonPopupDOMReferences",
    value: function getReportReasonPopupDOMReferences() {
      return this.reportReasonPopupDOMReferences;
    }
  }]);

  return PopupFactory;
}();

function init() {
  var reportReasonPopup = new PopupFactory();
  var reportReasonPopupDOMReferences = reportReasonPopup.getReportReasonPopupDOMReferences();
  /*const formController = */

  new _formController__WEBPACK_IMPORTED_MODULE_4__["default"](reportReasonPopupDOMReferences.reportReasonPopupForm); // const reportReasonPopupDOMWrapper = reportReasonPopup.getReportReasonPopupDOMWrapper();

  console.warn('reportReasonPopupDOMReferences: ', reportReasonPopupDOMReferences
  /*, ' /reportReasonPopupDOMWrapper: ', reportReasonPopupDOMWrapper*/
  );
  return {
    /*reportReasonPopupDOMWrapper,*/
    reportReasonPopupDOMReferences: reportReasonPopupDOMReferences,
    showReportReasonPopup: reportReasonPopup.showReportReasonPopup.bind(reportReasonPopup)
  };
} // export { reportReasonPopupDOMReferences, reportReasonPopupDOMWrapper };


/* harmony default export */ __webpack_exports__["default"] = (init); // export default bootstrapReportReasonPopup;

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