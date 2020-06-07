(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./src/bootstrap.js":
/*!**************************!*\
  !*** ./src/bootstrap.js ***!
  \**************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _popupFactory__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./popupFactory */ "./src/popupFactory.js");
/* harmony import */ var _popupController__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./popupController */ "./src/popupController.js");



var bootstrapReportReasonPopup = function bootstrapReportReasonPopup() {
  var _init = Object(_popupFactory__WEBPACK_IMPORTED_MODULE_0__["default"])(),
      reportReasonPopupDOMWrapper = _init.reportReasonPopupDOMWrapper,
      reportReasonPopupDOMReferences = _init.reportReasonPopupDOMReferences;

  var _PopupController = new _popupController__WEBPACK_IMPORTED_MODULE_1__["default"](reportReasonPopupDOMReferences.reportReasonPopupForm, reportReasonPopupDOMWrapper),
      reportReasonFlagButtonHandler = _PopupController.reportReasonFlagButtonHandler;

  return reportReasonFlagButtonHandler;
};

/* harmony default export */ __webpack_exports__["default"] = (bootstrapReportReasonPopup);

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
/* harmony import */ var _popupController__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./popupController */ "./src/popupController.js");



 // import submitForm from "./formController";


var FLAG_BTN_NAME_SUFFIX = 'doflag';

var PopupFactory = /*#__PURE__*/function () {
  function PopupFactory() {
    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, PopupFactory);

    this.reportReasonPopupDOMWrapper = null;
    this.reportReasonPopupDOMReferences = null;
    this.initOffClickHandler();
    this.initReasonList();
    this.initButtons();
    this.initPopupContainer();
    this.initReportReasonPopupDOMWrapper();
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
      var cancelButton = this.reportReasonPopupDOMWrapper.querySelector('#cancelReportReason');
      cancelButton.addEventListener('click', this.hideReportReasonPopup);
      var sendButton = this.reportReasonPopupDOMWrapper.querySelector('#sendReportReason');
      sendButton.addEventListener('click', submitForm);
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
    key: "hideReportReasonPopup",
    value: function hideReportReasonPopup() {
      this.reportReasonPopupDOMReferences.reportReasonSuccessInfo.classList.remove('report-reason-popup__success-info--show');
      this.reportReasonPopupDOMWrapper.classList.remove('report-reason-wrapper--show');
      this.reportReasonPopupDOMReferences.customReportReason.classList.remove('report-reason-popup__custom-report-reason--show');
      this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove('report-reason-popup--hide');
      this.reportReasonPopupDOMReferences.reportReasonValidationError.classList.remove('report-reason-popup__validation-error--show');
      this.reportReasonPopupDOMReferences.reportReasonPopupForm.reset();
    }
  }, {
    key: "getReportReasonPopupDOMWrapper",
    value: function getReportReasonPopupDOMWrapper() {
      return this.reportReasonPopupDOMWrapper;
    }
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
  var reportReasonPopupDOMWrapper = reportReasonPopup.getReportReasonPopupDOMWrapper();
  console.warn('reportReasonPopupDOMReferences: ', reportReasonPopupDOMReferences, ' /reportReasonPopupDOMWrapper: ', reportReasonPopupDOMWrapper);
  return {
    reportReasonPopupDOMWrapper: reportReasonPopupDOMWrapper,
    reportReasonPopupDOMReferences: reportReasonPopupDOMReferences
  };
} // export { reportReasonPopupDOMReferences, reportReasonPopupDOMWrapper };


/* harmony default export */ __webpack_exports__["default"] = (init); // export default bootstrapReportReasonPopup;

/***/ })

}]);
//# sourceMappingURL=1.script.js.map