// TODO: load it lazily
import bootstrapReportReasonPopup from './helpers/popupController';

document.addEventListener(
  'DOMContentLoaded',
  function initReportReasonPlugin() {
    bootstrapReportReasonPopup();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener(
      'click',
      bootstrapReportReasonPopup.handler,
      true /* use capture phase to fire handler before Q2A listeners on (un)flag buttons will */
    );
  }
);
