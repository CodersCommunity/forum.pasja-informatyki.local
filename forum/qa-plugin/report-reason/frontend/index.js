// TODO: remove dynamic import feature from Babel/Webpack if [defer] approach on this <script>
import bootstrapReportReasonPopup from './src/bootstrap';
const onClick = bootstrapReportReasonPopup();

const eventDelegationRoot = document.querySelector('.qa-main');
eventDelegationRoot.addEventListener(
	'click',
	onClick,
	true /* use capture phase to fire handler before Q2A listeners on flag buttons will */
);
