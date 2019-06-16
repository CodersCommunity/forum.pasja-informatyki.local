import reasonsCollection from '../data/reasonsCollection';

const listItemsDOM = reasonsCollection.reduce((listItems, reason, index, reasonsCollection) => {
    const reasonItemId = `reportReasonItem${ index }`;
    const isLast = index === reasonsCollection.length - 1;
    const textAreaDOM = isLast && `<textarea id="customReportReason" rows="3" cols="47" name="reportReason" class="report-reason-popup__custom-report-reason" data-requirable="true"></textarea>`;

    return listItems += `
        <li>
            <label for="${ reasonItemId }">
                <input id="${ reasonItemId }" type="radio" value="${ reason.value }" name="reportReason" data-requirable="true">
                ${ reason.description }
            </label>
            ${
                isLast ? textAreaDOM : ''
            }
        </li>
    `;
}, '');

const reportReasonPopupDOMWrapper = (function createReportReasonPopupWrapper() {
    const popupWrapper = document.createElement('div');
    popupWrapper.classList.add('report-reason-wrapper');
    popupWrapper.innerHTML = `
        <link href="../qa-plugin/report-reason/reportReason.css" rel="stylesheet" type="text/css">
         
        <div id="reportReasonPopup" class="report-reason-popup">
            <p>Zaznacz proszę powód zgłoszenia lub podaj własny:</p>
            
            <form method="post" class="report-reason-popup__form">
                <ul id="reportReasonList" class="report-reason-popup__list">${ listItemsDOM }</ul>
            
                <p id="reportReasonValidationError" class="report-reason-popup__validation-error">Nie zaznaczono powodu zgłoszenia!</p>
                
                <input id="cancelReportReason" type="button" value="Anuluj" class="report-reason-popup__button report-reason-popup__button--cancel">
                <button id="sendReportReason" type="submit" class="report-reason-popup__button report-reason-popup__button--save">Wyślij</button>
            </form>
        </div>
        <div id="reportReasonSuccessInfo" class="report-reason-popup__success-info">
            Zgłoszenie zostało wysłane.
            <button id="closeReportReasonSentInfo" class="report-reason-popup__button report-reason-popup__button--close" type="button">Zamknij</button>
        </div>
    `;

    return popupWrapper;
}());

export const reportReasonPopupDOMReferences = {
    reportReasonPopup: reportReasonPopupDOMWrapper.querySelector('#reportReasonPopup'),
    reportReasonPopupForm: reportReasonPopupDOMWrapper.querySelector('form'),
    customReportReason: reportReasonPopupDOMWrapper.querySelector('#customReportReason'),
    reportReasonSuccessInfo: reportReasonPopupDOMWrapper.querySelector('#reportReasonSuccessInfo'),
    requirableFormElements: reportReasonPopupDOMWrapper.querySelectorAll('[data-requirable="true"]'),
    reportReasonValidationError: reportReasonPopupDOMWrapper.querySelector('#reportReasonValidationError')
};

export default reportReasonPopupDOMWrapper;