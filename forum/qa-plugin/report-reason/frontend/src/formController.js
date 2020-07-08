import sendReport from './ajaxService';

const { NOTICE_LENGTH, POPUP_LABELS, ERROR_CODES } = FLAG_REASONS_METADATA;

class FormController {
	constructor() {
		this.buildForm();
		this.initReportReasonValidationErrorDOM();
		this.initReasonList();
		this.initTextArea();
		this.initFormInvalidityListenerAPI();

		this.requestIntegerKeys = ['postId', 'questionId', 'relativeParentPostId', 'reasonId'];
	}

	buildForm() {
		const formDOM = document.createElement('form');
		formDOM.method = 'post';
		formDOM.classList.add('report-reason-popup__form');
		formDOM.innerHTML = this.getFormHTML(this.getReasonListHTML());

		this.formDOM = formDOM;
	}

	getReasonListHTML() {
		return Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce(
			(listItems, [reasonKey, reasonValue], index, flagReasonsCollection) => {
				const isLast = index === flagReasonsCollection.length - 1;
				const textAreaDOM = isLast && this.getTextAreaHTML();

				return listItems + this.getListItemsHTML({ reasonKey, reasonValue, index, isLast, textAreaDOM });
			},
			''
		);
	}

	initReasonList() {
		const reportReasonList = this.formDOM.querySelector('#reportReasonList');
		reportReasonList.addEventListener('change', ({ target }) => {
			this.reportReasonValidationErrorDOM.classList.remove('display-block');

			const isLastReasonChosen = reportReasonList.children[reportReasonList.children.length - 1].contains(target);
			this.formDOM.elements.customReportReason.parentNode.classList.toggle('display-none', !isLastReasonChosen);
			this.formDOM.elements.customReportReason.required = isLastReasonChosen;
			this.toggleFormDisability({
				cancelReportReason: false,
				sendReportReason: false,
			});

			if (isLastReasonChosen) {
				this.formDOM.elements.customReportReason.focus();
			}
		});
	}

	initReportReasonValidationErrorDOM() {
		this.reportReasonValidationErrorDOM = this.formDOM.querySelector('#reportReasonValidationError');
	}

	initTextArea() {
		this.customReportReasonCharCounter = this.formDOM.querySelector('#customReportReasonCharCounter');
		this.formDOM.elements.customReportReason.addEventListener('input', ({ target }) => {
			this.toggleFormDisability({
				cancelReportReason: false,
				sendReportReason: false,
			});
			this.reportReasonValidationErrorDOM.classList.remove('display-block');
			this.customReportReasonCharCounter.textContent = NOTICE_LENGTH - target.value.length;
		});
	}

	initFormInvalidityListenerAPI() {
		this.formInvalidityListenerAPI = {
			_handler: (event) => {
				event.preventDefault();
			},
			attach: () => {
				this.formDOM.addEventListener('invalid', this.formInvalidityListenerAPI._handler, true);
			},
			detach: () => {
				this.formDOM.removeEventListener('invalid', this.formInvalidityListenerAPI._handler, true);
			},
		};
	}

	resetCustomReportReasonCharCounter() {
		this.customReportReasonCharCounter.textContent = NOTICE_LENGTH;
	}

	getTextAreaHTML() {
		return `
			<div id="customReportReasonWrapper" class="report-reason-popup__custom-report-reason-wrapper display-none">
				<small class="report-reason-popup__custom-report-reason-char-counter-wrapper">
					${POPUP_LABELS.CHAR_COUNTER_INFO}
					<output id="customReportReasonCharCounter">${NOTICE_LENGTH}</output>
				</small>
				<textarea id="customReportReason"
					class="report-reason-popup__custom-report-reason"
					name="reportReason"
					maxlength="${NOTICE_LENGTH}"
					rows="3"
					cols="47"></textarea>
			</div>`;
	}

	getListItemsHTML({ reasonKey, reasonValue, index, isLast, textAreaDOM }) {
		return `
			<li>
				<input id="${reasonKey}" 
						type="radio" 
						value="${index}" 
						name="reportReason"
						required>
				<label for="${reasonKey}">${reasonValue}</label>
				${isLast ? textAreaDOM : ''}
			</li>`;
	}

	getFormHTML(listItemsDOM) {
		return `
			<fieldset>
				<legend>${POPUP_LABELS.HEADER}</legend>
				<ul id="reportReasonList" class="report-reason-popup__list">${listItemsDOM}</ul>
	
				<span id="reportReasonValidationError" class="report-reason-popup__validation-error">${ERROR_CODES.GENERIC_ERROR}</span>
	
				<div class="report-reason-popup-buttons">
					<button id="cancelReportReason"
						type="button"
						class="report-reason-popup__button report-reason-popup__button--cancel">${POPUP_LABELS.CANCEL}</button>
					<button id="sendReportReason"
						type="submit"
						class="report-reason-popup__button report-reason-popup__button--save">${POPUP_LABELS.SEND}</button>
				</div>
			</fieldset>`;
	}

	getFormDOM() {
		return this.formDOM;
	}

	getReportReasonValidationErrorDOM() {
		return this.reportReasonValidationErrorDOM;
	}

	initButtons({ collectForumPostMetaData, hideReportReasonPopup, onAjaxSuccess, showFeedbackPopup }) {
		this.formDOM.elements.cancelReportReason.addEventListener('click', hideReportReasonPopup);
		this.formDOM.elements.sendReportReason.addEventListener('click', (event) => {
			this.handleReportResult(
				this.submitForm(event, collectForumPostMetaData()),
				onAjaxSuccess,
				showFeedbackPopup
			);
		});
	}

	prepareFormData(collectedForumPostMetaData) {
		const [reasonId, notice] = new FormData(this.formDOM).getAll('reportReason');

		return {
			...collectedForumPostMetaData,
			reasonId,
			notice,
			reportType: 'addFlag',
		};
	}

	normalizeIntegerProps(props) {
		return Object.entries(props).reduce((normalizedProps, [key, value]) => {
			if (this.requestIntegerKeys.includes(key)) {
				normalizedProps[key] = parseInt(value);
			}

			return normalizedProps;
		}, props);
	}

	enableForm() {
		this.toggleFormDisability({
			fieldset: false,
			cancelReportReason: false,
			sendReportReason: false,
		});
	}

	toggleFormDisability(states = {}) {
		this.formDOM.firstElementChild.disabled = !!states.fieldset;
		this.formDOM.cancelReportReason.disabled = !!states.cancelReportReason;
		this.formDOM.sendReportReason.disabled = !!states.sendReportReason;
	}

	submitForm(event, collectedForumPostMetaData) {
		event.preventDefault();

		const formElemsDisabilityState = { sendReportReason: true };
		const formValidationErrorCode = this.validateForm();

		if (formValidationErrorCode) {
			this.toggleFormDisability(formElemsDisabilityState);
			return Promise.reject({ formValidationErrorCode });
		}

		const formData = this.prepareFormData(collectedForumPostMetaData);
		this.toggleSendWaitingState(true);

		formElemsDisabilityState.cancelReportReason = true;
		formElemsDisabilityState.fieldset = true;
		this.toggleFormDisability(formElemsDisabilityState);

		return sendReport(this.normalizeIntegerProps(formData)).then((response) => ({ ...response, formData }));
	}

	handleReportResult(reportResult, onAjaxSuccess, showFeedbackPopup) {
		reportResult
			.then((response) => {
				if (typeof response.newFlags === 'string' && response.newFlags.length) {
					this.enableForm();
					onAjaxSuccess(response);
				} else {
					return Promise.reject(response);
				}
			})
			.catch((reason) => {
				if (reason.formValidationErrorCode) {
					this.handleReportReasonError(reason);
				} else {
					this.enableForm();
					this.handleReportReasonError(reason, showFeedbackPopup);
				}
			})
			.finally(() => {
				this.toggleSendWaitingState(false);
			});
	}

	handleReportReasonError(reason, showFeedbackPopup) {
		const errorCode = reason.formValidationErrorCode || reason.processingFlagReasonError || reason;
		const errorContent = this.getErrorContent(errorCode);

		if (typeof showFeedbackPopup === 'function') {
			showFeedbackPopup(errorContent, this.shouldReloadPage(reason));
		} else {
			this.onFormSubmissionError(errorContent);
		}

		console.error('Report reason rejected: ', reason, ' /errorContent: ', errorContent);
	}

	shouldReloadPage(reason) {
		return reason && reason.processingFlagReasonError === 'PAGE_NEEDS_RELOAD';
	}

	validateForm() {
		if (this.formDOM.reportValidity()) {
			return '';
		}

		return this.formDOM.elements.customReportReason.validity.valid ? 'NO_REASON_CHECKED' : 'CUSTOM_REASON_EMPTY';
	}

	getErrorContent(errorCode) {
		if (!errorCode || errorCode instanceof Error || typeof errorCode.newFlags !== 'undefined') {
			return ERROR_CODES.GENERIC_ERROR;
		}

		if (errorCode.includes(':')) {
			const [errorCodeName, errorCodeValue] = errorCode.split(':');

			if (!ERROR_CODES[errorCodeName]) {
				return ERROR_CODES.GENERIC_ERROR;
			} else {
				return ERROR_CODES[errorCodeName] + errorCodeValue;
			}
		} else if (errorCode.includes(' ')) {
			return errorCode;
		} else {
			return ERROR_CODES[errorCode];
		}
	}

	onFormSubmissionError(errorContent) {
		this.reportReasonValidationErrorDOM.innerHTML = errorContent;
		this.reportReasonValidationErrorDOM.classList.add(
			'display-block',
			'report-reason-popup__validation-error--blink'
		);

		setTimeout(() => {
			this.reportReasonValidationErrorDOM.classList.remove('report-reason-popup__validation-error--blink');
		}, 1750);
	}

	toggleSendWaitingState(isWaiting) {
		const { sendReportReason } = this.formDOM;

		if (isWaiting) {
			window.qa_show_waiting_after(sendReportReason, true);
		} else {
			window.qa_hide_waiting(sendReportReason);
		}
	}
}

export default FormController;
