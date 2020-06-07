import {
	swapFlagBtn,
	updateCurrentPostFlags,
	getPostParentId
} from './misc';
import getUnFlagButtonHTML from "./unFlagButton";

const { POPUP_LABELS } = FLAG_REASONS_METADATA;

class PopupFactory {
	constructor({ toggleSendWaitingState, getFlagButtonDOM, getFormDOM }) {
		this.toggleSendWaitingState = toggleSendWaitingState;
		this.getFlagButtonDOM = getFlagButtonDOM;
		this.getFormDOM = getFormDOM;
		this.reportReasonPopupDOMWrapper = null;
		this.reportReasonPopupDOMReferences = null;

		this.initReportReasonPopupDOMWrapper();
		this.initPopupContainer();
		this.initPopupDOMReferences();
		this.initReasonList();
		this.initOffClickHandler();
		// this.initButtons(submitForm, collectForumPostMetaData);
	}

	initOffClickHandler() {
		this.reportReasonPopupDOMWrapper.addEventListener('click', (event) => {
			const checkDOMElementsId = (DOMElement) =>
				DOMElement.id === 'reportReasonPopup' || DOMElement.id === 'reportReasonSuccessInfo';
			const clickedOutsidePopup = !event.composedPath().some(checkDOMElementsId);

			if (clickedOutsidePopup) {
				this.hideReportReasonPopup();
			}
		});
	}

	initReasonList() {
		const { reasonList } = this.reportReasonPopupDOMReferences;
		reasonList.addEventListener('change', ({ target }) => {
			this.reportReasonPopupDOMReferences.reportReasonValidationError.classList.remove(
				'report-reason-popup__validation-error--show'
			);

			if (reasonList.children[reasonList.children.length - 1].contains(target)) {
				this.reportReasonPopupDOMReferences.customReportReason.classList.add(
					'report-reason-popup__custom-report-reason--show'
				);
				setTimeout(
					this.reportReasonPopupDOMReferences.customReportReason.focus.bind(
						this.reportReasonPopupDOMReferences.customReportReason
					)
				);
			} else {
				this.reportReasonPopupDOMReferences.customReportReason.classList.remove(
					'report-reason-popup__custom-report-reason--show'
				);
			}
		});
	}

	initButtons(submitForm, collectForumPostMetaData) {
		this.reportReasonPopupDOMReferences.cancelButton.addEventListener('click', this.hideReportReasonPopup.bind(this));
		this.reportReasonPopupDOMReferences.sendButton.addEventListener('click', (event) => {
			submitForm(event, collectForumPostMetaData())
				.then(this.onAjaxSuccess.bind(this), this.onAjaxError.bind(this))
				.catch(error => console.error('Error???: ', error));
		});
		this.reportReasonPopupDOMReferences.closeReportReasonSentInfo.addEventListener('click', this.hideReportReasonPopup.bind(this));
	}

	initPopupContainer() {
		const popupContainer = document.querySelector('.qa-body-wrapper');
		popupContainer.appendChild(this.reportReasonPopupDOMWrapper);
	}

	initReportReasonPopupDOMWrapper() {
		const popupWrapper = document.createElement('div');
		popupWrapper.classList.add('report-reason-wrapper');
		popupWrapper.innerHTML = this.getPopupWrapperHTML();

		const tempForm = popupWrapper.querySelector('#replaceableForm')
		tempForm.parentNode.replaceChild(this.getFormDOM(), tempForm);

		this.reportReasonPopupDOMWrapper = popupWrapper;
	}

	initPopupDOMReferences() {
		this.reportReasonPopupDOMReferences = {
			reportReasonPopup: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonPopup'),
			customReportReason: this.reportReasonPopupDOMWrapper.querySelector('#customReportReason'),
			reportReasonSuccessInfo: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonSuccessInfo'),
			reportReasonValidationError: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonValidationError'),
			reasonList: this.reportReasonPopupDOMWrapper.querySelector('#reportReasonList'),
			cancelButton: this.reportReasonPopupDOMWrapper.querySelector('#cancelReportReason'),
			sendButton: this.reportReasonPopupDOMWrapper.querySelector('#sendReportReason'),
			closeReportReasonSentInfo: this.reportReasonPopupDOMWrapper.querySelector('#closeReportReasonSentInfo'),
		};
	}

	getPopupWrapperHTML() {
		return `
			<div id="reportReasonPopup" class="report-reason-popup">
				<p>${POPUP_LABELS.HEADER}</p>
				
				<form id="replaceableForm"></form>
			</div>
			<div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
				${POPUP_LABELS.REPORT_SENT}
				<button id="closeReportReasonSentInfo"
					class="report-reason-popup__button report-reason-popup__button--close"
					type="button">${POPUP_LABELS.CLOSE}</button>
			</div>`;
	}

	showReportReasonPopup() {
		this.reportReasonPopupDOMWrapper.classList.add('report-reason-wrapper--show');
	}

	hideReportReasonPopup() {
		this.reportReasonPopupDOMReferences.reportReasonSuccessInfo.classList.remove(
			'report-reason-popup__success-info--show'
		);
		this.reportReasonPopupDOMWrapper.classList.remove('report-reason-wrapper--show');
		this.reportReasonPopupDOMReferences.customReportReason.classList.remove(
			'report-reason-popup__custom-report-reason--show'
		);
		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.remove('report-reason-popup--hide');
		this.reportReasonPopupDOMReferences.reportReasonValidationError.classList.remove(
			'report-reason-popup__validation-error--show'
		);
		this.getFormDOM().reset();
	}

	onAjaxSuccess({ response, formData, sendButton }) {
		console.warn('response:', response);

		this.toggleSendWaitingState(sendButton, false);
		updateCurrentPostFlags(response.currentFlags, formData);

		const flagButtonDOM = this.getFlagButtonDOM();

		swapFlagBtn(
			flagButtonDOM,
			getUnFlagButtonHTML({
				postType: formData.postType,
				questionId: formData.questionId,
				postId: formData.postId,
				parentId: getPostParentId(formData.postType, flagButtonDOM),
			})
		);
		this.showSuccessPopup();
	}

	onAjaxError({ ajaxError, sendButton }) {
		this.toggleSendWaitingState(sendButton, false);
		// TODO: add proper error handling
		console.warn('ajaxError:', ajaxError);
	}

	showSuccessPopup() {
		this.reportReasonPopupDOMReferences.reportReasonPopup.classList.add('report-reason-popup--hide');
		this.reportReasonPopupDOMReferences.reportReasonSuccessInfo.classList.add('report-reason-popup', 'report-reason-popup__success-info--show');
	}
}

export default PopupFactory;
