const { NOTICE_LENGTH, POPUP_LABELS } = FLAG_REASONS_METADATA;

export const swapElement = (referenceNode, html) => {
	const tmpParent = document.createElement('div');
	tmpParent.innerHTML = html;

	const newElement = tmpParent.removeChild(tmpParent.firstElementChild);
	referenceNode.parentNode.insertBefore(newElement, referenceNode);
	referenceNode.remove();

	// return newElement;
};

export const elementsHTMLMap = new Map([
	[
		'textarea',
		`<textarea id="customReportReason"
			class="report-reason-popup__custom-report-reason"
			name="reportReason"
			data-requirable="true"
			maxlength="${NOTICE_LENGTH}"
			rows="3"
			cols="47"></textarea>`,
	],
	[
		'getListItem',
		({ reasonKey, reasonValue, index, isLast, textAreaDOM }) => {
			return `
				<!-- TODO: handle checking inputs while tabbing -->
				<li tabindex="1">
					<label for="${reasonKey}">
						<input id="${reasonKey}" 
								type="radio" 
								value="${index}" 
								name="reportReason" 
								data-requirable="true">
						${reasonValue}
					</label>
					${isLast ? textAreaDOM : ''}
				</li>
			`;
		},
	],
	[
		'getPopupWrapper',
		(listItemsDOM) => {
			return `
				<div id="reportReasonPopup" class="report-reason-popup">
					<p>${POPUP_LABELS.HEADER}</p>
					
					<form method="post" class="report-reason-popup__form">
						<ul id="reportReasonList" class="report-reason-popup__list">${listItemsDOM}</ul>
					
						<p id="reportReasonValidationError" class="report-reason-popup__validation-error">${POPUP_LABELS.NO_REASON_CHECKED}</p>
						
						<!-- TODO: why its input not button? -->
						<input id="cancelReportReason" type="button" value="${POPUP_LABELS.CANCEL}" class="report-reason-popup__button report-reason-popup__button--cancel">
						<button id="sendReportReason" type="submit" class="report-reason-popup__button report-reason-popup__button--save">${POPUP_LABELS.SEND}</button>
					</form>
				</div>
				<div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
					${POPUP_LABELS.REPORT_SENT}
					<button id="closeReportReasonSentInfo" class="report-reason-popup__button report-reason-popup__button--close" type="button">${POPUP_LABELS.CLOSE}</button>
				</div>`;
		},
	],
]);
