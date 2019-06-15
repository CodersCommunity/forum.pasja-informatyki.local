import bootstrapReportReasonPopup from './helpers/reportReasonPopupController';

document.addEventListener("DOMContentLoaded", function initReportReasonPlugin() {
    bootstrapReportReasonPopup();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener('click', bootstrapReportReasonPopup.handler, true);
});