import FlagController from './flagController';
import FormController from './formController';
import PopupController from './popupController';

const bootstrapReportReasonPopup = () => {
	const flagController = new FlagController();
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

export default bootstrapReportReasonPopup;
