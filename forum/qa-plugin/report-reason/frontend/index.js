import { bootstrapReportReasonPopup, postFlagReasonWrapper } from './src/bootstrap';

const onClick = bootstrapReportReasonPopup();
postFlagReasonWrapper();

const eventDelegationRoot = document.querySelector('.qa-main');
eventDelegationRoot.addEventListener(
	'click',
	onClick,
	true /* use capture phase to fire handler before Q2A listeners on flag buttons will */
);
