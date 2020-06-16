import sendAjax from "./ajaxService";

const { NOTICE_LENGTH, POPUP_LABELS } = FLAG_REASONS_METADATA;

class FormController {
	constructor() {
		this.buildForm();
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

	getTextAreaHTML() {
		return `
			<textarea id="customReportReason"
				class="report-reason-popup__custom-report-reason"
				name="reportReason"
				data-requirable="true"
				maxlength="${NOTICE_LENGTH}"
				rows="3"
				cols="47"></textarea>`;
	}

	getListItemsHTML({ reasonKey, reasonValue, index, isLast, textAreaDOM }) {
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
			</li>`;
	}

	getFormHTML(listItemsDOM) {
		return `
			<ul id="reportReasonList" class="report-reason-popup__list">${ listItemsDOM }</ul>

			<p id="reportReasonValidationError" class="report-reason-popup__validation-error">${POPUP_LABELS.NO_REASON_CHECKED}</p>

			<button id="cancelReportReason"
				type="button"
				class="report-reason-popup__button report-reason-popup__button--cancel">${POPUP_LABELS.CANCEL}</button>
			<button id="sendReportReason"
				type="submit"
				class="report-reason-popup__button report-reason-popup__button--save">${POPUP_LABELS.SEND}</button>`;
	}

	getFormDOM() {
		return this.formDOM;
	}

	validateForm(sendButton) {
		const requirableFormElements = [...this.formDOM.querySelectorAll('[data-requirable="true"]')];
		const isAnyFormElementUsed = requirableFormElements.some((element) => {
			const isCheckedRadioInput = element.type === 'radio' && element.value !== 'custom' && element.checked;
			const isFilledTextArea = element.tagName.toLowerCase() === 'textarea' && element.value;

			return isCheckedRadioInput || isFilledTextArea;
		});

		if (!isAnyFormElementUsed) {
			this.notifyAboutValidationError(sendButton);
		}

		return isAnyFormElementUsed;
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

	submitForm(event, collectedForumPostMetaData) {
		event.preventDefault();

		const sendButton = event.target;
		sendButton.blur();

		const isFormValid = this.validateForm(sendButton);
		if (!isFormValid) {
			// TODO: return more precise error
			return Promise.reject('Form is invalid!');
		}

		const boundToggleSendWaitingState = this.toggleSendWaitingState.bind(this, sendButton);
		boundToggleSendWaitingState(true);

		const formData = this.prepareFormData(collectedForumPostMetaData);

		return sendAjax(formData).then(
			(response) => ({response, formData, boundToggleSendWaitingState}),
			(ajaxError) => ({ajaxError, boundToggleSendWaitingState})
		);
	}

	notifyAboutValidationError(sendButton) {
		reportReasonValidationError.classList.add('report-reason-popup__validation-error--show');
		sendButton.classList.add('report-reason-popup__button--save--validation-blink');
		setTimeout(() => {
			sendButton.classList.remove('report-reason-popup__button--save--validation-blink');
		}, 1000);
	}

	toggleSendWaitingState(buttonReference, isWaiting) {
		if (isWaiting) {
			buttonReference.disabled = true;
			window.qa_show_waiting_after(buttonReference, true);
		} else {
			window.qa_hide_waiting(buttonReference);
			buttonReference.disabled = false;
		}
	}
}

export default FormController;
