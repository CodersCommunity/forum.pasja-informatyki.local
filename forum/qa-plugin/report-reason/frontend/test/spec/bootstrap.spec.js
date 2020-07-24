global.FLAG_REASONS_METADATA = {
	REASON_LIST: ['test'],
	NOTICE_LENGTH: -1,
	ERROR_CODES: {},
	POPUP_LABELS: {},
};

const { expect } = require('chai');
const { JSDOM } = require('jsdom');
const { bootstrapReportReasonPopup, postFlagReasonWrapper } = require('../cjs-src/bootstrap');

const { window } = new JSDOM();

global.window = window;
global.document = window.document;

describe('bootstrap', () => {
	describe('bootstrapReportReasonPopup()', () => {
		it('should return bound onClick method with one argument', () => {
			const boundOnClickMethod = bootstrapReportReasonPopup();

			expect(boundOnClickMethod).to.have.a.property('name').that.equals('bound onClick');
			expect(boundOnClickMethod).to.have.a.property('length').that.equals(1);
		});
	});

	describe('postFlagReasonWrapper()', () => {
		it('should be a function with one argument', () => {
			expect(postFlagReasonWrapper).to.be.a('function');
			expect(postFlagReasonWrapper).to.have.a.property('length').that.equals(1);
		});

		describe('in context of DOM manipulation', () => {
			const WRAPPED_REASON_CLAZZ = 'wrapped-reason';
			let flagReasonCustomItem = null;

			beforeEach(() => {
				flagReasonCustomItem = document.createElement('div');
				flagReasonCustomItem.textContent = 'x'.repeat(51 /* value higher than WRAP_FROM_LENGTH constant */);
				flagReasonCustomItem.classList.add('qa-item-flag-reason-item--custom');
				document.body.appendChild(flagReasonCustomItem);
			});

			afterEach(() => {
				const parent = document.body;
				while (parent.lastChild) {
					parent.lastChild.remove();
				}

				flagReasonCustomItem = null;
			});

			it('should call wrapper function on DOMContentLoaded event when runImmediately argument is falsy/omitted', (done) => {
				postFlagReasonWrapper();

				expect(flagReasonCustomItem.classList.contains(WRAPPED_REASON_CLAZZ)).to.be.false;

				triggerEventAndNotifyWhenListenerIsCalled('DOMContentLoaded').then(() => {
					expect(flagReasonCustomItem.classList.contains(WRAPPED_REASON_CLAZZ)).to.be.true;
					done();
				});
			});

			it('should call wrapper function immediately when argument runImmediately is truthy', () => {
				expect(flagReasonCustomItem.classList.contains(WRAPPED_REASON_CLAZZ)).to.be.false;

				postFlagReasonWrapper(true);

				expect(flagReasonCustomItem.classList.contains(WRAPPED_REASON_CLAZZ)).to.be.true;
			});
		});
	});

	after(() => {
		window.close();
	});
});

function triggerEventAndNotifyWhenListenerIsCalled(event) {
	return new Promise((resolve) => {
		document.addEventListener(event, resolve, { once: true });
		document.dispatchEvent(new window.Event(event));
	});
}
