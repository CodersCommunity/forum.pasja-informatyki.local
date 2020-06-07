const swapFlagBtn = (referenceBtn, btnHTML) => {
	const tmpParent = document.createElement('div');
	tmpParent.innerHTML = btnHTML;

	const newBtn = tmpParent.removeChild(tmpParent.firstElementChild);
	referenceBtn.parentNode.replaceChild(newBtn, referenceBtn);
};

function updateCurrentPostFlags(currentFlagsHTML, { postType, postId }) {
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

function getPostParentId(postType, flagButtonDOM) {
	if (postType !== 'c') {
		return null;
	}

	const parentElement = flagButtonDOM.closest('[id*="_list"]');

	return parentElement ?
		parentElement.id.match(/\d+/)[0] :
		null;
}

export { swapFlagBtn, updateCurrentPostFlags, getPostParentId };
