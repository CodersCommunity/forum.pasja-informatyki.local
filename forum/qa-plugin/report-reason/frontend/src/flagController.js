const FLAG_BTN_NAME_SUFFIX = 'doflag';

class FlagController {
	constructor() {
		this.flagButtonDOM = null;
		this.regex = {
			question: /q_doflag/,
			answer: /^a(\d+)_doflag/,
			comment: /^c(\d+)_doflag/,
			doComment: /^a(\d+)_docomment/,
		};
	}

	onClick(showReportReasonPopup, event) {
		if (event.target.name && event.target.name.endsWith(FLAG_BTN_NAME_SUFFIX)) {
			event.preventDefault();
			event.stopPropagation();

			this.flagButtonDOM = event.target;
			// this.reportReasonPopupForm = event.target.form.action;
			showReportReasonPopup();
		}
	}

	getPostIdFromInputName(postType, inputName) {
		// TODO: check if it works (changed exec to match)...
		const [, postId] = inputName.match(this.regex[postType]);
		return postId;
	}

	recognizeInputKindByName(inputName) {
		const [mappedInputNameRegexKey] = Object.entries(this.regex).find(([regexKey, regexValue]) =>
			regexValue.test(inputName)
		);
		return mappedInputNameRegexKey;
	}

	collectForumPostMetaData() {
		const postType = this.recognizeInputKindByName(this.flagButtonDOM.name);
		const postRootSource = this.flagButtonDOM.form.getAttribute('action');
		const postMetaData = {
			questionId: postRootSource.split('/')[1],
			postType: postType.slice(0, 1),
		};
		postMetaData.postId = this.getPostIdFromInputName(postType, this.flagButtonDOM.name) || postMetaData.questionId;

		return postMetaData;
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

	getFlagButtonDOM() {
		return this.flagButtonDOM;
	}
}

export default FlagController;
