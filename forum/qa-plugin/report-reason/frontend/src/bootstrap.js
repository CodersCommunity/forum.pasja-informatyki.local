import FlagController from './flagController';
import FormController from './formController';
import PopupController from './popupController';

const bootstrapReportReasonPopup = () => {
	const flagController = new FlagController(postFlagReasonWrapper);
	const formController = new FormController();
	const popupController = new PopupController({
		getFlagButtonDOM: flagController.getFlagButtonDOM.bind(flagController),
		formInvalidityListenerAPI: formController.formInvalidityListenerAPI,
		getFormDOM: formController.getFormDOM.bind(formController),
		getPostParentId: flagController.getPostParentId.bind(flagController),
		swapFlagBtn: flagController.swapFlagBtn.bind(flagController),
		updateCurrentPostFlags: flagController.updateCurrentPostFlags.bind(flagController),
		getReportReasonValidationErrorDOM: formController.getReportReasonValidationErrorDOM.bind(formController),
		resetCustomReportReasonCharCounter: formController.resetCustomReportReasonCharCounter.bind(formController),
	});

	formController.initButtons({
		collectForumPostMetaData: flagController.collectForumPostMetaData.bind(flagController),
		hideReportReasonPopup: popupController.hideReportReasonPopup.bind(popupController),
		onAjaxSuccess: popupController.onAjaxSuccess.bind(popupController),
		showFeedbackPopup: popupController.showFeedbackPopup.bind(popupController),
	});

	return flagController.onClick.bind(flagController, popupController.showReportReasonPopup.bind(popupController));
};

const postFlagReasonWrapper = (() => {
	const WRAPPED_REASON_CLAZZ = 'wrapped-reason';
	const WRAP_FROM_LENGTH = 50;

	return function wrapPostFlagReasons(runImmediately) {
		if (runImmediately) {
			wrap();
		} else {
			document.addEventListener('DOMContentLoaded', wrap);
		}
	};

	function wrap() {
		document.querySelectorAll('.qa-item-flag-reason-item--custom').forEach((item) => {
			if (item.textContent.length > WRAP_FROM_LENGTH) {
				item.classList.add(WRAPPED_REASON_CLAZZ);
				item.addEventListener('click', unWrap, { once: true });
			}
		});
	}

	function unWrap({ target }) {
		target.classList.remove(WRAPPED_REASON_CLAZZ);
	}
})();

export { bootstrapReportReasonPopup, postFlagReasonWrapper as wrapPostFlagReasons };
