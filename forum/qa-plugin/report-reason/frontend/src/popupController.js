import getUnFlagButtonHTML from './unFlagButton';

const { POPUP_LABELS, ERROR_CODES } = FLAG_REASONS_METADATA;

class PopupController {
	constructor({
		getFlagButtonDOM,
		formInvalidityListenerAPI,
		getFormDOM,
		resetForm,
		getPostParentId,
		swapFlagBtn,
		updateCurrentPostFlags,
		getReportReasonValidationErrorDOM,
		resetCustomReportReasonCharCounter,
	}) {
		this.getFlagButtonDOM = getFlagButtonDOM;
		this.formInvalidityListenerAPI = formInvalidityListenerAPI;
		this.getFormDOM = getFormDOM;
		this.resetForm = resetForm;
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
		document.body.appendChild(this.reportReasonPopupDOMWrapper);
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
			reloadPage: this.reportReasonPopupDOMWrapper.querySelector('#reloadPage'),
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
				<button id="reloadPage"
					class="report-reason-popup__button report-reason-popup__button--reload display-none"
					type="button">${POPUP_LABELS.RELOAD}</button>
			</div>
		`;
	}

	showReportReasonPopup() {
		this.formInvalidityListenerAPI.attach();
		this.reportReasonPopupDOMWrapper.classList.remove('display-none');
		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove('display-none');
		this.getFormDOM().elements.namedItem('reportReason-0').focus();
		document.body.classList.add('disable-scroll');
	}

	hideReportReasonPopup() {
		this.formInvalidityListenerAPI.detach();

		this.reportReasonPopupDOMWrapper.classList.add('display-none');
		this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.add('display-none');
		this.reportReasonPopupDOMReferences.customReportReason.parentNode.classList.add('display-none');
		this.getReportReasonValidationErrorDOM().classList.remove('display-block');
		document.body.classList.remove('disable-scroll');

		this.resetForm();
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

	showFeedbackPopup(feedbackContent, reloadPageOnPopupClose) {
		this.reportReasonPopupDOMReferences.reportReasonRequestInfo.innerHTML = feedbackContent;

		this.toggleFeedbackButton(reloadPageOnPopupClose);
		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.add('display-none');
		this.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.remove('display-none');
		this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.focus();
	}

	toggleFeedbackButton(reloadPageOnPopupClose) {
		if (reloadPageOnPopupClose) {
			this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.classList.add('display-none');
			this.reportReasonPopupDOMReferences.reloadPage.classList.remove('display-none');
		}
	}

	initSuccessPopupCloseBtn() {
		this.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.addEventListener(
			'click',
			this.hideReportReasonPopup.bind(this)
		);
		this.reportReasonPopupDOMReferences.reloadPage.addEventListener('click', () => window.location.reload(true));
	}
}

export default PopupController;
