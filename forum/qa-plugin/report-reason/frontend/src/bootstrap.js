import FlagController from './flagController';
import FormController from './formController';
import PopupFactory from "./popupFactory";

const bootstrapReportReasonPopup = () => {
	const flagController = new FlagController();
	const formController = new FormController(flagController.toggleSendWaitingState);
	const reportReasonPopup = new PopupFactory({
		toggleSendWaitingState: flagController.toggleSendWaitingState,
		getFlagButtonDOM: flagController.getFlagButtonDOM.bind(flagController),
		getFormDOM: formController.getFormDOM.bind(formController)
	});

	reportReasonPopup.initButtons(formController.submitForm.bind(formController), flagController.collectForumPostMetaData.bind(flagController));

	return flagController.onClick.bind(flagController, reportReasonPopup.showReportReasonPopup.bind(reportReasonPopup));
};

export default bootstrapReportReasonPopup;
