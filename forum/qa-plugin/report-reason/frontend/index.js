document.addEventListener('DOMContentLoaded', async function initReportReasonPlugin() {
	// TODO: try..catch it
	const { default: bootstrapReportReasonPopup } = await import('./src/bootstrap');
	const onClick = bootstrapReportReasonPopup();

	console.warn('onClick: ', onClick);

	const eventDelegationRoot = document.querySelector('.qa-main');
	eventDelegationRoot.addEventListener(
		'click',
		onClick,
		true /* use capture phase to fire handler before Q2A listeners on flag buttons will */
	);
});
