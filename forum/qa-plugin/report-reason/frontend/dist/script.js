!function(t){var e={};function o(n){if(e[n])return e[n].exports;var r=e[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=t,o.c=e,o.d=function(t,e,n){o.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},o.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)o.d(n,r,function(e){return t[e]}.bind(null,r));return n},o.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(e,"a",e),e},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},o.p="../qa-plugin/report-reason/frontend/dist/",o(o.s=9)}([function(t,e,o){var n=o(4),r=o(5),a=o(6),s=o(8);t.exports=function(t,e){return n(t)||r(t,e)||a(t,e)||s()}},function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function o(t,e){for(var o=0;o<e.length;o++){var n=e[o];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}t.exports=function(t,e,n){return e&&o(t.prototype,e),n&&o(t,n),t}},function(t,e){t.exports=function(t,e,o){return e in t?Object.defineProperty(t,e,{value:o,enumerable:!0,configurable:!0,writable:!0}):t[e]=o,t}},function(t,e){t.exports=function(t){if(Array.isArray(t))return t}},function(t,e){t.exports=function(t,e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t)){var o=[],n=!0,r=!1,a=void 0;try{for(var s,i=t[Symbol.iterator]();!(n=(s=i.next()).done)&&(o.push(s.value),!e||o.length!==e);n=!0);}catch(t){r=!0,a=t}finally{try{n||null==i.return||i.return()}finally{if(r)throw a}}return o}}},function(t,e,o){var n=o(7);t.exports=function(t,e){if(t){if("string"==typeof t)return n(t,e);var o=Object.prototype.toString.call(t).slice(8,-1);return"Object"===o&&t.constructor&&(o=t.constructor.name),"Map"===o||"Set"===o?Array.from(t):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?n(t,e):void 0}}},function(t,e){t.exports=function(t,e){(null==e||e>t.length)&&(e=t.length);for(var o=0,n=new Array(e);o<e;o++)n[o]=t[o];return n}},function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},function(t,e,o){"use strict";o.r(e);var n=o(0),r=o.n(n),a=o(1),s=o.n(a),i=o(2),p=o.n(i),u=function(){function t(e){s()(this,t),this.postFlagReasonWrapper=e,this.flagButtonDOM=null,this.regex={question:/q_doflag/,answer:/^a(\d+)_doflag/,comment:/^c(\d+)_doflag/,doComment:/^a(\d+)_docomment/}}return p()(t,[{key:"onClick",value:function(t,e){e.target.name&&e.target.name.endsWith("doflag")&&(e.preventDefault(),e.stopPropagation(),this.flagButtonDOM=e.target,t())}},{key:"getPostIdFromInputName",value:function(t,e){var o=e.match(this.regex[t]);return r()(o,2)[1]}},{key:"recognizeInputKindByName",value:function(t){var e=Object.entries(this.regex).find((function(e){var o=r()(e,2);o[0];return o[1].test(t)}));return r()(e,1)[0]}},{key:"collectForumPostMetaData",value:function(){var t=this.recognizeInputKindByName(this.flagButtonDOM.name),e={questionId:this.flagButtonDOM.form.getAttribute("action").split("/")[1],postType:t.slice(0,1)};return e.postId=this.getPostIdFromInputName(t,this.flagButtonDOM.name)||e.questionId,e.relativeParentPostId=this.getPostParentId(e.postType,this.flagButtonDOM)||e.postId,e.code=this.flagButtonDOM.form.elements.code.value,e}},{key:"getFlagButtonDOM",value:function(){return this.flagButtonDOM}},{key:"getPostParentId",value:function(t,e){if("c"!==t)return null;var o=e.closest('[id$="_list"]');return o?o.id.match(/\d+/)[0]:null}},{key:"swapFlagBtn",value:function(t,e){var o=document.createElement("div");o.innerHTML=e;var n=o.removeChild(o.firstElementChild);t.parentNode.replaceChild(n,t)}},{key:"updateCurrentPostFlags",value:function(t,e){var o=this.prepareFlagsUpdate(t,e),n=o.flags,r=o.flagsAlreadyExist,a=o.flagsParent,s=o.flagsAlternatePlace;return n?(r?a.parentNode.replaceChild(n,a):s?a.insertBefore(n,s.nextElementSibling):a.appendChild(n),this.postFlagReasonWrapper(!0),!0):(console.error("Report reason response does not have new flags: ",t),!1)}},{key:"prepareFlagsUpdate",value:function(t,e){var o=e.postType,n=e.postId,r="q"===o?"view":"item",a=".qa-".concat(o,"-").concat(r,"-"),s="#".concat(o).concat(n," ").concat(a),i=(new DOMParser).parseFromString(t,"text/html").querySelector("".concat(a,"flags")),p=document.querySelector("".concat(s,"flags")),u=p||document.querySelector("".concat(s,"meta")),c=u.querySelector('[class$="-who"]');return{flags:i,flagsAlreadyExist:!!p,flagsParent:u,flagsAlternatePlace:c}}}]),t}(),c=o(3),l=o.n(c);var d=function(t){return fetch("/report-flag",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify(t)}).then((function(t){return t.ok?t.json():Promise.reject(t.text())}))};function f(t,e){var o=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),o.push.apply(o,n)}return o}function R(t){for(var e=1;e<arguments.length;e++){var o=null!=arguments[e]?arguments[e]:{};e%2?f(Object(o),!0).forEach((function(e){l()(t,e,o[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(o)):f(Object(o)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(o,e))}))}return t}var h=FLAG_REASONS_METADATA,m=h.NOTICE_LENGTH,g=h.POPUP_LABELS,v=h.ERROR_CODES,y=function(){function t(){s()(this,t),this.buildForm(),this.initReportReasonValidationErrorDOM(),this.initReasonList(),this.initTextArea(),this.initFormInvalidityListenerAPI(),this.requestIntegerKeys=["postId","questionId","relativeParentPostId","reasonId"]}return p()(t,[{key:"buildForm",value:function(){var t=document.createElement("form");t.method="post",t.classList.add("report-reason-popup__form"),t.innerHTML=this.getFormHTML(this.getReasonListHTML()),this.formDOM=t}},{key:"getReasonListHTML",value:function(){var t=this;return Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce((function(e,o,n,a){var s=r()(o,2),i=s[0],p=s[1],u=n===a.length-1,c=u&&t.getTextAreaHTML();return e+t.getListItemsHTML({reasonKey:i,reasonValue:p,index:n,isLast:u,textAreaDOM:c})}),"")}},{key:"initReasonList",value:function(){var t=this,e=this.formDOM.querySelector("#reportReasonList");e.addEventListener("change",(function(o){var n=o.target;t.reportReasonValidationErrorDOM.classList.remove("display-block");var r=e.children[e.children.length-1].contains(n);t.formDOM.elements.customReportReason.parentNode.classList.toggle("display-none",!r),t.formDOM.elements.customReportReason.required=r,t.toggleFormDisability({cancelReportReason:!1,sendReportReason:!1}),r&&t.formDOM.elements.customReportReason.focus()}))}},{key:"initReportReasonValidationErrorDOM",value:function(){this.reportReasonValidationErrorDOM=this.formDOM.querySelector("#reportReasonValidationError")}},{key:"initTextArea",value:function(){var t=this;this.customReportReasonCharCounter=this.formDOM.querySelector("#customReportReasonCharCounter"),this.formDOM.elements.customReportReason.addEventListener("input",(function(e){var o=e.target;t.toggleFormDisability({cancelReportReason:!1,sendReportReason:!1}),t.reportReasonValidationErrorDOM.classList.remove("display-block"),t.customReportReasonCharCounter.textContent=m-o.value.length}))}},{key:"initFormInvalidityListenerAPI",value:function(){var t=this;this.formInvalidityListenerAPI={_handler:function(t){t.preventDefault()},attach:function(){t.formDOM.addEventListener("invalid",t.formInvalidityListenerAPI._handler,!0)},detach:function(){t.formDOM.removeEventListener("invalid",t.formInvalidityListenerAPI._handler,!0)}}}},{key:"resetCustomReportReasonCharCounter",value:function(){this.customReportReasonCharCounter.textContent=m}},{key:"getTextAreaHTML",value:function(){return'\n\t\t\t<div id="customReportReasonWrapper" class="report-reason-popup__custom-report-reason-wrapper display-none">\n\t\t\t\t<small class="report-reason-popup__custom-report-reason-char-counter-wrapper">\n\t\t\t\t\t'.concat(g.CHAR_COUNTER_INFO,'\n\t\t\t\t\t<output id="customReportReasonCharCounter">').concat(m,'</output>\n\t\t\t\t</small>\n\t\t\t\t<textarea id="customReportReason"\n\t\t\t\t\tclass="report-reason-popup__custom-report-reason"\n\t\t\t\t\tname="reportReason"\n\t\t\t\t\tmaxlength="').concat(m,'"\n\t\t\t\t\trows="3"\n\t\t\t\t\tcols="47"></textarea>\n\t\t\t</div>')}},{key:"getListItemsHTML",value:function(t){var e=t.reasonKey,o=t.reasonValue,n=t.index,r=t.isLast,a=t.textAreaDOM;return'\n\t\t\t<li>\n\t\t\t\t<label for="'.concat(e,'">\n\t\t\t\t\t<input id="').concat(e,'" \n\t\t\t\t\t\t\ttype="radio" \n\t\t\t\t\t\t\tvalue="').concat(n,'" \n\t\t\t\t\t\t\tname="reportReason"\n\t\t\t\t\t\t\trequired>\n\t\t\t\t\t').concat(o,"\n\t\t\t\t</label>\n\t\t\t\t").concat(r?a:"","\n\t\t\t</li>")}},{key:"getFormHTML",value:function(t){return"\n\t\t\t<fieldset>\n\t\t\t\t<legend>".concat(g.HEADER,'</legend>\n\t\t\t\t<ul id="reportReasonList" class="report-reason-popup__list">').concat(t,'</ul>\n\t\n\t\t\t\t<span id="reportReasonValidationError" class="report-reason-popup__validation-error">').concat(v.GENERIC_ERROR,'</span>\n\t\n\t\t\t\t<div class="report-reason-popup-buttons">\n\t\t\t\t\t<button id="cancelReportReason"\n\t\t\t\t\t\ttype="button"\n\t\t\t\t\t\tclass="report-reason-popup__button report-reason-popup__button--cancel">').concat(g.CANCEL,'</button>\n\t\t\t\t\t<button id="sendReportReason"\n\t\t\t\t\t\ttype="submit"\n\t\t\t\t\t\tclass="report-reason-popup__button report-reason-popup__button--save">').concat(g.SEND,"</button>\n\t\t\t\t</div>\n\t\t\t</fieldset>")}},{key:"getFormDOM",value:function(){return this.formDOM}},{key:"getReportReasonValidationErrorDOM",value:function(){return this.reportReasonValidationErrorDOM}},{key:"initButtons",value:function(t){var e=this,o=t.collectForumPostMetaData,n=t.hideReportReasonPopup,r=t.onAjaxSuccess,a=t.showFeedbackPopup;this.formDOM.elements.cancelReportReason.addEventListener("click",n),this.formDOM.elements.sendReportReason.addEventListener("click",(function(t){e.handleReportResult(e.submitForm(t,o()),r,a)}))}},{key:"prepareFormData",value:function(t){var e=new FormData(this.formDOM).getAll("reportReason"),o=r()(e,2),n=o[0],a=o[1];return R(R({},t),{},{reasonId:n,notice:a,reportType:"addFlag"})}},{key:"normalizeIntegerProps",value:function(t){var e=this;return Object.entries(t).reduce((function(t,o){var n=r()(o,2),a=n[0],s=n[1];return e.requestIntegerKeys.includes(a)&&(t[a]=parseInt(s)),t}),t)}},{key:"enableForm",value:function(){this.toggleFormDisability({fieldset:!1,cancelReportReason:!1,sendReportReason:!1})}},{key:"toggleFormDisability",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};this.formDOM.firstElementChild.disabled=!!t.fieldset,this.formDOM.cancelReportReason.disabled=!!t.cancelReportReason,this.formDOM.sendReportReason.disabled=!!t.sendReportReason}},{key:"submitForm",value:function(t,e){t.preventDefault();var o={sendReportReason:!0},n=this.validateForm();if(n)return this.toggleFormDisability(o),Promise.reject({formValidationErrorCode:n});var r=this.prepareFormData(e);return this.toggleSendWaitingState(!0),o.cancelReportReason=!0,o.fieldset=!0,this.toggleFormDisability(o),d(this.normalizeIntegerProps(r)).then((function(t){return R(R({},t),{},{formData:r})}))}},{key:"handleReportResult",value:function(t,e,o){var n=this;t.then((function(t){if("string"!=typeof t.newFlags||!t.newFlags.length)return Promise.reject(t);n.enableForm(),e(t)})).catch((function(t){t.formValidationErrorCode?n.handleReportReasonError(t):(n.enableForm(),n.handleReportReasonError(t,o))})).finally((function(){n.toggleSendWaitingState(!1)}))}},{key:"handleReportReasonError",value:function(t,e){var o=t.formValidationErrorCode||t.processingFlagReasonError||t,n=this.getErrorContent(o);"function"==typeof e?e(n,this.shouldReloadPage(t)):this.onFormSubmissionError(n),console.error("Report reason rejected: ",t," /errorContent: ",n)}},{key:"shouldReloadPage",value:function(t){return t&&"PAGE_NEEDS_RELOAD"===t.processingFlagReasonError}},{key:"validateForm",value:function(){return this.formDOM.reportValidity()?"":this.formDOM.elements.customReportReason.validity.valid?"NO_REASON_CHECKED":"CUSTOM_REASON_EMPTY"}},{key:"getErrorContent",value:function(t){if(!t||t instanceof Error||void 0!==t.newFlags)return v.GENERIC_ERROR;if(t.includes(":")){var e=t.split(":"),o=r()(e,2),n=o[0],a=o[1];return v[n]?v[n]+a:v.GENERIC_ERROR}return t.includes(" ")?t:v[t]}},{key:"onFormSubmissionError",value:function(t){var e=this;this.reportReasonValidationErrorDOM.innerHTML=t,this.reportReasonValidationErrorDOM.classList.add("display-block","report-reason-popup__validation-error--blink"),setTimeout((function(){e.reportReasonValidationErrorDOM.classList.remove("report-reason-popup__validation-error--blink")}),1750)}},{key:"toggleSendWaitingState",value:function(t){var e=this.formDOM.sendReportReason;t?window.qa_show_waiting_after(e,!0):window.qa_hide_waiting(e)}}]),t}();var O,b,P,D=function(t){var e=t.postType,o=t.questionId,n=t.postId,r=t.parentId,a=e+n,s="";switch(e){case"q":s="qa_show_waiting_after(this, false)",a=e;break;case"a":s="return qa_answer_click(".concat(n,", ").concat(o,", this);");break;case"c":s="return qa_comment_click(".concat(n,", ").concat(o,", ").concat(r,", this);");break;default:throw new Error("Unrecognized postType: ".concat(e," for questionId: ").concat(o," and postId: ").concat(n))}return'\n\t\t<input name="'.concat(a).concat("_dounflag",'" \n\t\t\tonclick="').concat(s,'"\n\t\t\tvalue="').concat("wycofaj zgłoszenie",'"\n\t\t\ttitle="').concat("Wycofaj zgłoszenie tej treści",'"\n\t\t\ttype="').concat("submit",'" \n\t\t\tclass="').concat("qa-form-light-button qa-form-light-button-unflag",'">\n\t')},M=FLAG_REASONS_METADATA,E=M.POPUP_LABELS,F=M.ERROR_CODES,k=function(){function t(e){var o=e.getFlagButtonDOM,n=e.formInvalidityListenerAPI,r=e.getFormDOM,a=e.getPostParentId,i=e.swapFlagBtn,p=e.updateCurrentPostFlags,u=e.getReportReasonValidationErrorDOM,c=e.resetCustomReportReasonCharCounter;s()(this,t),this.getFlagButtonDOM=o,this.formInvalidityListenerAPI=n,this.getFormDOM=r,this.getPostParentId=a,this.swapFlagBtn=i,this.updateCurrentPostFlags=p,this.getReportReasonValidationErrorDOM=u,this.resetCustomReportReasonCharCounter=c,this.reportReasonPopupDOMWrapper=null,this.reportReasonPopupDOMReferences=null,this.initReportReasonPopupDOMWrapper(),this.initPopupContainer(),this.initPopupDOMReferences(),this.initOffClickHandler(),this.initSuccessPopupCloseBtn()}return p()(t,[{key:"initOffClickHandler",value:function(){var t=this;this.reportReasonPopupDOMWrapper.addEventListener("click",(function(e){!e.composedPath().some((function(t){return"reportReasonPopup"===t.id||"reportReasonRequestFeedback"===t.id}))&&t.hideReportReasonPopup()}))}},{key:"initPopupContainer",value:function(){document.body.appendChild(this.reportReasonPopupDOMWrapper)}},{key:"initReportReasonPopupDOMWrapper",value:function(){var t=document.createElement("div");t.classList.add("report-reason-wrapper","display-none"),t.innerHTML=this.getPopupWrapperHTML();var e=t.querySelector("#replaceableForm");e.parentNode.replaceChild(this.getFormDOM(),e),this.reportReasonPopupDOMWrapper=t}},{key:"initPopupDOMReferences",value:function(){this.reportReasonPopupDOMReferences={reportReasonPopup:this.reportReasonPopupDOMWrapper.querySelector("#reportReasonPopup"),customReportReason:this.reportReasonPopupDOMWrapper.querySelector("#customReportReason"),reportReasonRequestFeedback:this.reportReasonPopupDOMWrapper.querySelector("#reportReasonRequestFeedback"),reportReasonRequestInfo:this.reportReasonPopupDOMWrapper.querySelector("#reportReasonRequestInfo"),closeReportReasonRequestFeedback:this.reportReasonPopupDOMWrapper.querySelector("#closeReportReasonRequestFeedback"),reloadPage:this.reportReasonPopupDOMWrapper.querySelector("#reloadPage")}}},{key:"getPopupWrapperHTML",value:function(){return'\n\t\t\t<div id="reportReasonPopup" class="report-reason-popup">\n\t\t\t\t<form id="replaceableForm"></form>\n\t\t\t</div>\n\t\t\t\n\t\t\t<div id="reportReasonRequestFeedback" class="report-reason-popup report-reason-popup__request-feedback display-none">\n\t\t\t\t<div id="reportReasonRequestInfo" class="report-reason-popup__request-feedback-info"></div>\n\t\t\t\t<button id="closeReportReasonRequestFeedback"\n\t\t\t\t\tclass="report-reason-popup__button report-reason-popup__button--close"\n\t\t\t\t\ttype="button">'.concat(E.CLOSE,'</button>\n\t\t\t\t<button id="reloadPage"\n\t\t\t\t\tclass="report-reason-popup__button report-reason-popup__button--reload display-none"\n\t\t\t\t\ttype="button">').concat(E.RELOAD,"</button>\n\t\t\t</div>\n\t\t")}},{key:"showReportReasonPopup",value:function(){this.formInvalidityListenerAPI.attach(),this.reportReasonPopupDOMWrapper.classList.remove("display-none"),this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove("display-none"),this.getFormDOM().elements.reportReason[0].focus(),document.body.classList.add("disable-scroll")}},{key:"hideReportReasonPopup",value:function(){this.formInvalidityListenerAPI.detach(),this.reportReasonPopupDOMWrapper.classList.add("display-none"),this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.add("display-none"),this.reportReasonPopupDOMReferences.customReportReason.parentNode.classList.add("display-none"),this.getReportReasonValidationErrorDOM().classList.remove("display-block"),document.body.classList.remove("disable-scroll");var t=this.getFormDOM();t.reset(),t.elements.customReportReason.required=!1,t.elements.cancelReportReason.disabled=!1,t.elements.sendReportReason.disabled=!1,this.resetCustomReportReasonCharCounter(),this.getReportReasonValidationErrorDOM().innerHTML=F.GENERIC_ERROR}},{key:"onAjaxSuccess",value:function(t){var e=t.newFlags,o=t.formData,n=this.getFlagButtonDOM(),r=this.updateCurrentPostFlags(e,o)?E.REPORT_SENT:F.GENERIC_ERROR;r===E.REPORT_SENT&&this.swapFlagBtn(n,D({postType:o.postType,questionId:o.questionId,postId:o.postId,parentId:this.getPostParentId(o.postType,n)})),this.showFeedbackPopup(r)}},{key:"showFeedbackPopup",value:function(t,e){this.reportReasonPopupDOMReferences.reportReasonRequestInfo.innerHTML=t,this.toggleFeedbackButton(e),this.reportReasonPopupDOMReferences.reportReasonPopup.classList.add("display-none"),this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.remove("display-none"),this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.focus()}},{key:"toggleFeedbackButton",value:function(t){t&&(this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.classList.add("display-none"),this.reportReasonPopupDOMReferences.reloadPage.classList.remove("display-none"))}},{key:"initSuccessPopupCloseBtn",value:function(){this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.addEventListener("click",this.hideReportReasonPopup.bind(this)),this.reportReasonPopupDOMReferences.reloadPage.addEventListener("click",(function(){return window.location.reload(!0)}))}}]),t}(),_=function(){return function(e){e?t():document.addEventListener("DOMContentLoaded",t)};function t(){document.querySelectorAll(".qa-item-flag-reason-item--custom").forEach((function(t){t.textContent.length>50&&(t.classList.add("wrapped-reason"),t.addEventListener("click",e,{once:!0}))}))}function e(t){t.target.classList.remove("wrapped-reason")}}(),L=(O=new u(_),b=new y,P=new k({getFlagButtonDOM:O.getFlagButtonDOM.bind(O),formInvalidityListenerAPI:b.formInvalidityListenerAPI,getFormDOM:b.getFormDOM.bind(b),getPostParentId:O.getPostParentId.bind(O),swapFlagBtn:O.swapFlagBtn.bind(O),updateCurrentPostFlags:O.updateCurrentPostFlags.bind(O),getReportReasonValidationErrorDOM:b.getReportReasonValidationErrorDOM.bind(b),resetCustomReportReasonCharCounter:b.resetCustomReportReasonCharCounter.bind(b)}),b.initButtons({collectForumPostMetaData:O.collectForumPostMetaData.bind(O),hideReportReasonPopup:P.hideReportReasonPopup.bind(P),onAjaxSuccess:P.onAjaxSuccess.bind(P),showFeedbackPopup:P.showFeedbackPopup.bind(P)}),O.onClick.bind(O,P.showReportReasonPopup.bind(P)));_(),document.querySelector(".qa-main").addEventListener("click",L,!0)}]);