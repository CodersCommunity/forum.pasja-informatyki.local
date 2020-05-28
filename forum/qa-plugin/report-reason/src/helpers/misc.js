const noticeLength = FLAG_REASONS_MAP.NOTICE_LENGTH || 256;

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
    `<textarea id="customReportReason" maxlength="${noticeLength}" cols="47" name="reportReason" class="report-reason-popup__custom-report-reason" data-requirable="true"></textarea>`,
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
                <p>Zaznacz powód zgłoszenia lub podaj własny:</p>
                
                <form method="post" class="report-reason-popup__form">
                    <ul id="reportReasonList" class="report-reason-popup__list">${listItemsDOM}</ul>
                
                    <p id="reportReasonValidationError" class="report-reason-popup__validation-error">Nie zaznaczono powodu zgłoszenia!</p>
                    
                    <input id="cancelReportReason" type="button" value="Anuluj" class="report-reason-popup__button report-reason-popup__button--cancel">
                    <button id="sendReportReason" type="submit" class="report-reason-popup__button report-reason-popup__button--save">Wyślij</button>
                </form>
            </div>
            <div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
                Zgłoszenie zostało wysłane.
                <button id="closeReportReasonSentInfo" class="report-reason-popup__button report-reason-popup__button--close" type="button">Zamknij</button>
            </div>`;
    },
  ],
]);
