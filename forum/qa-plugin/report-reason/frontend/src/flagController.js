const FLAG_BTN_NAME_SUFFIX = 'doflag';

class FlagController {
	constructor(postFlagReasonWrapper) {
		this.postFlagReasonWrapper = postFlagReasonWrapper;
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

		return parentElement ? parentElement.id.match(/\d+/)[0] : null;
	}

	swapFlagBtn(referenceBtn, btnHTML) {
		const tmpParent = document.createElement('div');
		tmpParent.innerHTML = btnHTML;

		const newBtn = tmpParent.removeChild(tmpParent.firstElementChild);
		referenceBtn.parentNode.replaceChild(newBtn, referenceBtn);
	}

	updateCurrentPostFlags(newFlagsHTML, postMetadata) {
		const { flags, flagsAlreadyExist, flagsParent, flagsAlternatePlace } = this.prepareFlagsUpdate(
			newFlagsHTML,
			postMetadata
		);

		if (!flags) {
			console.error('Report reason response does not have new flags: ', newFlagsHTML);
			return false;
		}

		if (flagsAlreadyExist) {
			flagsParent.parentNode.replaceChild(flags, flagsParent);
		} else {
			if (flagsAlternatePlace) {
				flagsParent.insertBefore(flags, flagsAlternatePlace.nextElementSibling);
			} else {
				flagsParent.appendChild(flags);
			}
		}

		this.postFlagReasonWrapper(true);

		return true;
	}

	prepareFlagsUpdate(flagsHTML, { postType, postId }) {
		const relativeClassNamePart = postType === 'q' ? 'view' : 'item';
		const classNamePartSuffix = `.qa-${postType}-${relativeClassNamePart}-`;
		const classNamePart = `#${postType}${postId} ${classNamePartSuffix}`;
		const flagsDOM = new DOMParser()
			.parseFromString(flagsHTML, 'text/html')
			.querySelector(`${classNamePartSuffix}flags`);
		const postFlagsWrapper = document.querySelector(`${classNamePart}flags`);
		const flagsParent = postFlagsWrapper || document.querySelector(`${classNamePart}meta`);
		const flagsAlternatePlace = flagsParent.querySelector('[class$="-who"]');

		return {
			flags: flagsDOM,
			flagsAlreadyExist: !!postFlagsWrapper,
			flagsParent,
			flagsAlternatePlace,
		};
	}
}

export default FlagController;
