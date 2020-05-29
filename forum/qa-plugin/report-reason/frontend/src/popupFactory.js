import { elementsHTMLMap } from './misc';

const reportReasonPopupDOMWrapper = (function () {
  const listItemsDOM = Object.entries(FLAG_REASONS_METADATA.REASON_LIST).reduce(
    (listItems, [reasonKey, reasonValue], index, flagReasonsCollection) => {
      // const reasonItemId = `reportReasonItem${index}`;
      const isLast = index === flagReasonsCollection.length - 1;
      const textAreaDOM = isLast && elementsHTMLMap.get('textarea');

      return (
        listItems +
        elementsHTMLMap.get('getListItem')({
          reasonKey,
          reasonValue,
          index,
          isLast,
          textAreaDOM,
        })
      );
    },
    ''
  );

  const popupWrapper = document.createElement('div');
  popupWrapper.classList.add('report-reason-wrapper');
  popupWrapper.innerHTML = elementsHTMLMap.get('getPopupWrapper')(listItemsDOM);

  return popupWrapper;
})();

const reportReasonPopupDOMReferences = {
  reportReasonPopup: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonPopup'
  ),
  reportReasonPopupForm: reportReasonPopupDOMWrapper.querySelector('form'),
  customReportReason: reportReasonPopupDOMWrapper.querySelector(
    '#customReportReason'
  ),
  reportReasonSuccessInfo: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonSuccessInfo'
  ),
  requirableFormElements: reportReasonPopupDOMWrapper.querySelectorAll(
    '[data-requirable="true"]'
  ),
  reportReasonValidationError: reportReasonPopupDOMWrapper.querySelector(
    '#reportReasonValidationError'
  ),
};

export { reportReasonPopupDOMReferences, reportReasonPopupDOMWrapper };
