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

	getFlagButtonDOM() {
		return this.flagButtonDOM;
	}

	getPostParentId(postType, flagButtonDOM) {
		if (postType !== 'c') {
			return null;
		}

		const parentElement = flagButtonDOM.closest('[id*="_list"]');

		return parentElement ?
			parentElement.id.match(/\d+/)[0] :
			null;
	}

	swapFlagBtn(referenceBtn, btnHTML) {
		const tmpParent = document.createElement('div');
		tmpParent.innerHTML = btnHTML;

		const newBtn = tmpParent.removeChild(tmpParent.firstElementChild);
		referenceBtn.parentNode.replaceChild(newBtn, referenceBtn);
	}

	updateCurrentPostFlags(currentFlagsHTML, { postType, postId }) {
		const relativeClassNamePart = postType === 'q' ? 'view' : 'item';
		const classNamePart = `#${postType}${postId} .qa-${postType}-${relativeClassNamePart}-`;
		const postFlagsWrapper = document.querySelector(`${classNamePart}flags`);

		if (postFlagsWrapper) {
			postFlagsWrapper.outerHTML = currentFlagsHTML;
		} else {
			const targetElementSelector = `.qa-${postType}-item-flags`;
			const responseAsDOM = new DOMParser()
				.parseFromString(currentFlagsHTML, 'text/html')
				.querySelector(targetElementSelector);
			const postMetaWrapper = document.querySelector(`${classNamePart}meta`);
			postMetaWrapper.appendChild(responseAsDOM);
		}
	}
}

export default FlagController;
