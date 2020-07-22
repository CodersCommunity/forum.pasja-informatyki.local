const { expect } = require('chai');
const sinon = require('sinon');
const { default: sendReport } = require('../cjs-src/ajaxService');

const defaultFetchResponse = Object.freeze({
	value: {},

	text() {
		return new Promise((resolve) => resolve(String(this.value)));
	},
	json() {
		return new Promise((resolve) => resolve(JSON.stringify(this.value)));
	},
});
const { stub } = sinon;

global.fetch = stub();

describe('sendReport()', () => {
	beforeEach(() => fetch.reset());

	it('should return a promise', () => {
		const data = {};
		const fetchResolution = {
			...defaultFetchResponse,
			ok: true,
		};

		fetch.resolves(fetchResolution);

		expect(sendReport(data)).to.be.a('promise');
	});

	it('should return a rejected promise when fetch failed', (done) => {
		fetch.rejects();

		const rejectedPromise = sendReport();
		expect(rejectedPromise).to.be.a('promise');
		rejectedPromise.catch(() => done());
	});

	it('should return a rejected promise with serialized response when fetch failed', (done) => {
		const REJECTION_REASON = 'Response error';

		fetch.resolves({
			...defaultFetchResponse,
			value: REJECTION_REASON,
		});

		sendReport()
			.catch((reason) => {
				expect(reason).to.be.a('promise');

				return reason;
			})
			.then((handledRejection) => {
				expect(handledRejection).to.be.equal(REJECTION_REASON);

				done();
			});
	});

	it('should call fetch with appropriate parameters', (done) => {
		const data = { key: 'value' };

		fetch.resolves({
			...defaultFetchResponse,
			ok: true,
		});
		sendReport(data).then(() => done());

		const wasCalledWithParams = fetch.calledWith(
			'/report-flag',
			sinon.match((param) => param.body === JSON.stringify(data))
		);
		expect(wasCalledWithParams).to.be.true;
	});
});
