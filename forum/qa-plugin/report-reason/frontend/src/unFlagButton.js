function getUnFlagButtonHTML({ postType, questionId, postId, parentId }) {
	let namePrefix = postType + postId;
	let onclick = '';

	const nameSuffix = '_dounflag';
	const value = 'wycofaj zgłoszenie';
	const title = 'Wycofaj zgłoszenie tej treści';
	const type = 'submit';
	const clazz = 'qa-form-light-button qa-form-light-button-unflag';

	switch (postType) {
		case 'q': {
			onclick = 'qa_show_waiting_after(this, false)';
			namePrefix = postType;
			break;
		}
		case 'a': {
			onclick = `return qa_answer_click(${postId}, ${questionId}, this);`;
			break;
		}
		case 'c': {
			onclick = `return qa_comment_click(${postId}, ${questionId}, ${parentId}, this);`;
			break;
		}
		default: {
			throw new Error(`Unrecognized postType: ${postType} for questionId: ${questionId} and postId: ${postId}`);
		}
	}

	return `
		<input name="${namePrefix}${nameSuffix}" 
			onclick="${onclick}"
			value="${value}"
			title="${title}"
			type="${type}" 
			class="${clazz}">
	`;
}

export default getUnFlagButtonHTML;
