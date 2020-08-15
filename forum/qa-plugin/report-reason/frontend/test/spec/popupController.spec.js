const { default: PopupController } = require('../cjs-src/popupController');
const { stub, spy } = require('sinon');
const { expect } = require('chai');
const { JSDOM } = require('jsdom');

const { window } = new JSDOM();

describe('PopupController', () => {
	before(() => {
		global.document = window.document;
	});

	after(() => {
		global.document = null;
	});

	describe('initPopupContainer()', () => {
		let popupController = {};

		before(() => {
			const getFormDOMStub = stub();
			getFormDOMStub.returns(document.createElement('form'));

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
			});
		});

		after(() => {
			popupController = null;
			clearPageBody();
		});

		it('should append reportReasonPopupDOMWrapper to document.body', () => {
			const reportReasonPopupDOMWrapper = document.createElement('div');

			popupController.reportReasonPopupDOMWrapper = reportReasonPopupDOMWrapper;
			popupController.initPopupContainer();

			expect(document.body.contains(reportReasonPopupDOMWrapper));
		});
	});

	describe('initReportReasonPopupDOMWrapper()', () => {
		let popupController = {};
		let getPopupWrapperHTMLStub = () => {};
		let tempForm = {};

		before(() => {
			tempForm = document.createElement('form');
			tempForm.id = 'replaceableForm';
			const getFormDOMStub = stub();
			getFormDOMStub.returns(tempForm);

			getPopupWrapperHTMLStub = stub();
			getPopupWrapperHTMLStub.returns(tempForm.outerHTML);

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
			});
			popupController.getPopupWrapperHTML = getPopupWrapperHTMLStub;
		});

		after(() => {
			popupController = null;
			clearPageBody();
		});

		it('should set proper form into popupWrapper', () => {
			popupController.initReportReasonPopupDOMWrapper();

			expect(popupController.reportReasonPopupDOMWrapper.contains(tempForm)).to.be.true;
			expect(normalizeHTML(popupController.reportReasonPopupDOMWrapper.innerHTML)).to.equals(
				normalizeHTML(getPopupWrapperHTMLStub())
			);
		});
	});

	describe('showReportReasonPopup()', () => {
		let popupController = {};
		let formInvalidityListenerAPIAttachSpy = () => {};

		beforeEach(() => {
			const formDOM = document.createElement('form');
			const reportReasonDOM = document.createElement('input');

			reportReasonDOM.id = 'reportReason-0';
			formDOM.appendChild(reportReasonDOM);

			const getFormDOMStub = stub();
			getFormDOMStub.returns(formDOM);

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
			});

			popupController.formInvalidityListenerAPI = { attach() {} };
			formInvalidityListenerAPIAttachSpy = spy(popupController.formInvalidityListenerAPI, 'attach');
		});

		afterEach(() => {
			popupController = null;
			clearPageBody();
		});

		it('should call formInvalidityListenerAPI.attach', () => {
			popupController.showReportReasonPopup();

			expect(formInvalidityListenerAPIAttachSpy.calledOnce).to.be.true;
		});

		it('should appropriately modify DOM elements classes', () => {
			popupController.showReportReasonPopup();

			expect(popupController.reportReasonPopupDOMWrapper.classList.contains('display-none')).to.be.false;
			expect(popupController.reportReasonPopupDOMReferences.reportReasonPopup.classList.contains('display-none'))
				.to.be.false;
			expect(document.body.classList.contains('disable-scroll')).to.be.true;
		});

		it('should focus first form element', () => {
			const firstFormElement = popupController.getFormDOM().elements.namedItem('reportReason-0');

			expect(document.activeElement).not.equals(firstFormElement);

			popupController.showReportReasonPopup();

			expect(document.activeElement).equals(firstFormElement);
		});
	});

	describe('hideReportReasonPopup()', () => {
		let popupController = {};
		let formInvalidityListenerAPIDetachSpy = () => {};
		let resetFormSpy = () => {};
		let reportReasonValidationError = null;

		beforeEach(() => {
			const formDOM = document.createElement('form');
			const getFormDOMStub = stub();
			getFormDOMStub.returns(formDOM);

			reportReasonValidationError = document.createElement('div');

			resetFormSpy = spy();

			const getReportReasonValidationErrorDOMStub = stub();
			getReportReasonValidationErrorDOMStub.returns(reportReasonValidationError);

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
				resetForm: resetFormSpy,
				getReportReasonValidationErrorDOM: getReportReasonValidationErrorDOMStub,
				resetCustomReportReasonCharCounter: spy(),
			});

			popupController.formInvalidityListenerAPI = { detach() {} };
			formInvalidityListenerAPIDetachSpy = spy(popupController.formInvalidityListenerAPI, 'detach');
		});

		afterEach(() => {
			popupController = null;
			clearPageBody();
			formInvalidityListenerAPIDetachSpy = () => {};
			resetFormSpy = () => {};
			reportReasonValidationError = null;
		});

		it('should call formInvalidityListenerAPI.detach', () => {
			createBasicDOMStructure();
			popupController.hideReportReasonPopup();

			expect(formInvalidityListenerAPIDetachSpy.calledOnce).to.be.true;
		});

		it('should appropriately modify DOM elements classes', () => {
			createBasicDOMStructure();
			popupController.hideReportReasonPopup();

			expect(popupController.reportReasonPopupDOMWrapper.classList.contains('display-none')).to.be.true;
			expect(
				popupController.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.contains(
					'display-none'
				)
			).to.be.true;
			expect(
				popupController.reportReasonPopupDOMReferences.customReportReason.parentNode.classList.contains(
					'display-none'
				)
			).to.be.true;
			expect(popupController.getReportReasonValidationErrorDOM().classList.contains('display-block')).to.be.false;
			expect(document.body.classList.contains('disable-scroll')).to.be.false;
		});

		it('should reset form', () => {
			createBasicDOMStructure();
			popupController.hideReportReasonPopup();

			expect(resetFormSpy.calledOnce).to.be.true;
		});

		it('should call resetCustomReportReasonCharCounter', () => {
			createBasicDOMStructure();
			popupController.hideReportReasonPopup();

			expect(popupController.resetCustomReportReasonCharCounter.calledOnce).to.be.true;
		});

		it('should set innerHTML for reportReasonValidationError', () => {
			createBasicDOMStructure();
			popupController.hideReportReasonPopup();

			expect(reportReasonValidationError.innerHTML).to.equals(
				global.FLAG_REASONS_METADATA.ERROR_CODES.GENERIC_ERROR
			);
		});

		function createBasicDOMStructure() {
			popupController.reportReasonPopupDOMReferences.customReportReason = document.createElement('input');
			popupController.reportReasonPopupDOMReferences.customReportReason.name = 'customReportReason';

			const cancelReportReason = document.createElement('button');
			cancelReportReason.name = 'cancelReportReason';
			const sendReportReason = document.createElement('button');
			sendReportReason.name = 'sendReportReason';

			const formDOM = popupController.getFormDOM();
			formDOM.append(
				popupController.reportReasonPopupDOMReferences.customReportReason,
				cancelReportReason,
				sendReportReason
			);
		}
	});

	describe('onAjaxSuccess()', () => {
		let popupController = {};
		let getFlagButtonDOMSpy = () => {};
		let updateCurrentPostFlagsSpy = () => {};

		beforeEach(() => {
			const formDOM = document.createElement('form');

			const getFormDOMStub = stub();
			getFormDOMStub.returns(formDOM);

			getFlagButtonDOMSpy = spy();
			updateCurrentPostFlagsSpy = spy();

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
				getFlagButtonDOM: getFlagButtonDOMSpy,
				updateCurrentPostFlags: updateCurrentPostFlagsSpy,
			});
		});

		afterEach(() => {
			popupController = null;
			getFlagButtonDOMSpy = null;
			updateCurrentPostFlagsSpy = null;
			clearPageBody();
		});

		it('should call getFlagButtonDOM method', () => {
			popupController.onAjaxSuccess({});

			expect(getFlagButtonDOMSpy.calledOnce).to.be.true;
		});

		it('should call updateCurrentPostFlags method with two parameters', () => {
			const newFlags = 'flags-test';
			const formData = 'form-test';

			popupController.onAjaxSuccess({ newFlags, formData });

			expect(updateCurrentPostFlagsSpy.calledWith(newFlags, formData)).to.be.true;
		});

		it('should call method showFeedbackPopup with one parameter', () => {
			popupController.showFeedbackPopup = spy();
			popupController.onAjaxSuccess({});

			expect(popupController.showFeedbackPopup.calledWith(global.FLAG_REASONS_METADATA.ERROR_CODES.GENERIC_ERROR))
				.to.be.true;
		});
	});

	describe('showFeedbackPopup()', () => {
		let popupController = {};
		let toggleFeedbackButtonSpy = () => {};

		beforeEach(() => {
			const formDOM = document.createElement('form');

			const getFormDOMStub = stub();
			getFormDOMStub.returns(formDOM);

			toggleFeedbackButtonSpy = spy();

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
			});
		});

		afterEach(() => {
			popupController = null;
			toggleFeedbackButtonSpy = null;
			clearPageBody();
		});

		it('should set innerHTML of reportReasonRequestInfo', () => {
			const feedbackContent = '<span>test content</span>';

			popupController.showFeedbackPopup(feedbackContent, null);

			expect(popupController.reportReasonPopupDOMReferences.reportReasonRequestInfo.innerHTML).to.equals(
				feedbackContent
			);
		});

		it('should call toggleFeedbackButton with reloadPageOnPopupClose parameter', () => {
			const reloadPageOnPopupClose = true;

			popupController.toggleFeedbackButton = toggleFeedbackButtonSpy;
			popupController.showFeedbackPopup('', reloadPageOnPopupClose);

			expect(toggleFeedbackButtonSpy.calledWith(reloadPageOnPopupClose)).to.be.true;
		});

		it('should modify classNames approproately', () => {
			popupController.showFeedbackPopup();

			expect(popupController.reportReasonPopupDOMReferences.reportReasonPopup.classList.contains('display-none'))
				.to.be.true;
			expect(
				popupController.reportReasonPopupDOMReferences.reportReasonRequestFeedback.classList.contains(
					'display-none'
				)
			).to.be.false;
		});

		it('should set focus on closeReportReasonRequestFeedback element', () => {
			expect(document.activeElement).not.to.equals(
				popupController.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback
			);

			popupController.showFeedbackPopup();

			expect(document.activeElement).to.equals(
				popupController.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback
			);
		});
	});

	describe('toggleFeedbackButton()', () => {
		let popupController = {};

		beforeEach(() => {
			const formDOM = document.createElement('form');

			const getFormDOMStub = stub();
			getFormDOMStub.returns(formDOM);

			popupController = new PopupController({
				getFormDOM: getFormDOMStub,
			});
		});

		afterEach(() => {
			popupController = null;
			clearPageBody();
		});

		it('should modify classNames appropriately when reloadPageOnPopupClose parameter is falsy', () => {
			popupController.toggleFeedbackButton();

			expect(
				popupController.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.classList.contains(
					'display-none'
				)
			).to.be.false;
			expect(popupController.reportReasonPopupDOMReferences.reloadPage.classList.contains('display-none')).to.be
				.true;
		});

		it('should modify classNames appropriately when reloadPageOnPopupClose parameter is truthy', () => {
			popupController.toggleFeedbackButton(true);

			expect(
				popupController.reportReasonPopupDOMReferences.closeReportReasonRequestFeedback.classList.contains(
					'display-none'
				)
			).to.be.true;
			expect(popupController.reportReasonPopupDOMReferences.reloadPage.classList.contains('display-none')).to.be
				.false;
		});
	});
});

function clearPageBody() {
	const parent = document.body;
	while (parent.lastChild) {
		parent.lastChild.remove();
	}
}

function normalizeHTML(html) {
	return html.replace(/\s{2,}|\t|\n|\r/g, '');
}
