document.addEventListener(
  'DOMContentLoaded',
  async function initReportReasonPlugin() {
    const { default: bootstrapReportReasonPopup } = await import(
      './src/popupController'
    );
    bootstrapReportReasonPopup();

    const eventDelegationRoot = document.querySelector('.qa-main');
    eventDelegationRoot.addEventListener(
      'click',
      bootstrapReportReasonPopup.handler,
      true /* use capture phase to fire handler before Q2A listeners on flag buttons will */
    );
  }
);
