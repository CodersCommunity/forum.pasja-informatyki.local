import getUnFlagButtonHTML from './unFlagButton';

const { POPUP_LABELS, ERROR_CODES } = FLAG_REASONS_METADATA;

class PopupController {
	constructor({
		getFlagButtonDOM,
		formInvalidityListenerAPI,
		getFormDOM,
		getPostParentId,
		swapFlagBtn,
		updateCurrentPostFlags,
		getReportReasonValidationErrorDOM,
		resetCustomReportReasonCharCounter,
	}) {
		this.getFlagButtonDOM = getFlagButtonDOM;
		this.formInvalidityListenerAPI = formInvalidityListenerAPI;
		this.getFormDOM = getFormDOM;
		this.getPostParentId = getPostParentId;
		this.swapFlagBtn = swapFlagBtn;
		this.updateCurrentPostFlags = updateCurrentPostFlags;
		this.getReportReasonValidationErrorDOM = getReportReasonValidationErrorDOM;
		this.resetCustomReportReasonCharCounter = resetCustomReportReasonCharCounter;
		this.reportReasonPopupDOMWrapper = null;
		this.reportReasonPopupDOMReferences = null;

		this.initReportReasonPopupDOMWrapper();
		this.initPopupContainer();
		this.initPopupDOMReferences();
		this.initOffClickHandler();
		this.initSuccessPopupCloseBtn();
	}

	initOffClickHandler() {
		this.reportReasonPopupDOMWrapper.addEventListener('click', (event) => {
			const checkDOMElementsId = (DOMElement) =>
				DOMElement.id === 'reportReasonPopup' || DOMElement.id === 'reportReasonRequestFeedback';
			const clickedOutsidePopup = !event.composedPath().some(checkDOMElementsId);

			if (clickedOutsidePopup) {
				this.hideReportReasonPopup();
			}
		});
	}

	initPopupContainer() {
		const popupContainer = document.querySelector('.qa-body-wrapper');
		popupContainer.appendChild(this.reportReasonPopupDOMWrapper);
	}

	initReportReasonPopupDOMWrapper() {
		const popupWrapper = document.createElement('div');
		popupWrapper.classList.add('report-reason-wrapper', 'display-none');
		popupWrapper.innerHTML = this.getPopupWrapperHTML();

		const tempForm = popupWrapper.querySelector('#replaceableForm');
		tempForm.parentNode.replaceChild(this.getFormDOM(), tempForm);

		this.reportReasonPopupDOMWrapper = popupWrapper;
	}

	initPopupDOMReferences() {
		this.reportReasonPopupDOMReferences = {
			reportReasonPopup: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonPopup'),
			customReportReason: this.reportReasonPopupDOMWrapper.querySelector('#customReportReason'),
			reportReasonRequestFeedback: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonRequestFeedback'),
			reportReasonRequestInfo: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonRequestInfo'),
			closeReportReasonRequestFeedback: this.reportReasonPopupDOMWrapper.querySelector(
				'#closeReportReasonRequestFeedback'
			),
		};
	}

	getPopupWrapperHTML() {
		return `
			<div id="reportReasonPopup" class="report-reason-popup">
				<form id="replaceableForm"></form>
			</div>
			
			<div id="reportReasonRequestFeedback" class="report-reason-popup report-reason-popup__request-feedback display-none">
				<div id="reportReasonRequestInfo" class="report-reason-popup__request-feedback-info"></div>
				<button id="closeReportReasonRequestFeedback"
					class="report-reason-popup__button report-reason-popup__button--close"
					type="button">${POPUP_LABELS.CLOSE}</button>
			</div>
		`;
	}

	showReportReasonPopup() {
		this.formInvalidityListenerAPI.attach();
		this.reportReasonPopupDOMWrapper.classList.remove('display-none');
		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove('display-none');
		this.getFormDOM().elements.reportReason[0].focus();
	}

	hideReportReasonPopup() {
		this.formInvalidityListenerAPI.detach();

		this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.add('display-none');
		this.reportReasonPopupDOMWrapper.classList.add('display-none');
		this.reportReasonPopupDOMReferences.customReportReason.parentNode.classList.add('display-none');

		this.getReportReasonValidationErrorDOM().classList.remove('display-block');

		const formDOM = this.getFormDOM();
		formDOM.reset();
		formDOM.elements.customReportReason.required = false;
		formDOM.elements.sendReportReason.disabled = false;

		this.resetCustomReportReasonCharCounter();
		this.getReportReasonValidationErrorDOM().innerHTML = ERROR_CODES.GENERIC_ERROR;
	}

	onAjaxSuccess({ newFlags, formData }) {
		const flagButtonDOM = this.getFlagButtonDOM();
		const feedbackContent = this.updateCurrentPostFlags(newFlags, formData)
			? POPUP_LABELS.REPORT_SENT
			: ERROR_CODES.GENERIC_ERROR;

		if (feedbackContent === POPUP_LABELS.REPORT_SENT) {
			this.swapFlagBtn(
				flagButtonDOM,
				getUnFlagButtonHTML({
					postType: formData.postType,
					questionId: formData.questionId,
					postId: formData.postId,
					parentId: this.getPostParentId(formData.postType, flagButtonDOM),
				})
			);
		}

		this.showFeedbackPopup(feedbackContent);
	}

	showFeedbackPopup(feedbackContent) {
		this.reportReasonPopupDOMReferences.reportReasonRequestInfo.innerHTML = feedbackContent;

		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.add('display-none');
		this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.remove('display-none');

		this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.focus();
	}

	initSuccessPopupCloseBtn() {
		this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.addEventListener(
			'click',
			this.hideReportReasonPopup.bind(this)
		);
	}
}

export default PopupController;
