import FlagController from './flagController';
import FormController from './formController';
import PopupController from "./popupController";

const bootstrapReportReasonPopup = () => {
	const flagController = new FlagController();
	const formController = new FormController();
	const reportReasonPopup = new PopupController({
		getFlagButtonDOM: flagController.getFlagButtonDOM.bind(flagController),
		getFormDOM: formController.getFormDOM.bind(formController),
		getPostParentId: flagController.getPostParentId.bind(flagController),
		swapFlagBtn: flagController.swapFlagBtn.bind(flagController),
		updateCurrentPostFlags: flagController.updateCurrentPostFlags.bind(flagController)
	});

	reportReasonPopup.initButtons(
		formController.submitForm.bind(formController), flagController.collectForumPostMetaData.bind(flagController)
	);

	return flagController.onClick.bind(flagController, reportReasonPopup.showReportReasonPopup.bind(reportReasonPopup));
};

export default bootstrapReportReasonPopup;
