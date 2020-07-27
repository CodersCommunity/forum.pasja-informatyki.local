const { default: FlagController } = require('../cjs-src/flagController');
const { spy } = require('sinon');
const { expect } = require('chai');
const { JSDOM } = require('jsdom');

const { window } = new JSDOM();
const { document } = window;

describe('FlagController', () => {
	before(() => {
		global.document = document;
	});

	after(() => {
		global.document = null;
	});

	describe('onClick()', () => {
		const FLAG_BTN_NAME_SUFFIX = 'doflag';
		const dispatchAndListenToClickEvent = () => {
			const boundOnClick = flagController.onClick.bind(flagController, showReportReasonPopup);

			const eventTarget = document.createElement('div');
			eventTarget.name = FLAG_BTN_NAME_SUFFIX;

			const listenerPromise = new Promise((resolve) => {
				eventTarget.addEventListener('click', (event) => {
					resolve({
						boundOnClick,
						event,
						eventTarget,
					});
				});
			});

			eventTarget.click();

			return listenerPromise;
		};

		let flagController = {};
		let showReportReasonPopup = () => {};

		beforeEach(() => {
			showReportReasonPopup = spy(() => {});
			flagController = new FlagController(showReportReasonPopup);
		});

		afterEach(() => {
			flagController = null;
			showReportReasonPopup = () => {};
		});

		it("should prevent default event behavior and stop it's propagation", (done) => {
			dispatchAndListenToClickEvent().then(({ boundOnClick, event }) => {
				expect(event.cancelBubble).to.be.false;
				expect(event.defaultPrevented).to.be.false;

				boundOnClick(event);

				expect(event.cancelBubble).to.be.true;
				expect(event.defaultPrevented).to.be.true;

				done();
			});
		});

		it('should assign event.target to flagButtonDOM property', (done) => {
			dispatchAndListenToClickEvent().then(({ boundOnClick, event, eventTarget }) => {
				expect(flagController.flagButtonDOM).to.equal(null);

				boundOnClick(event);

				expect(flagController.flagButtonDOM).to.equal(eventTarget);

				done();
			});
		});

		it('should call showReportReasonPopup callback', (done) => {
			dispatchAndListenToClickEvent().then(({ boundOnClick, event, eventTarget }) => {
				expect(showReportReasonPopup.calledOnce).to.be.false;

				boundOnClick(event);

				expect(showReportReasonPopup.calledOnce).to.be.true;

				done();
			});
		});
	});

	describe('getPostIdFromInputName()', () => {
		let flagController = {};

		before(() => {
			flagController = new FlagController();
		});

		after(() => {
			flagController = null;
		});

		it('should return correct postId', () => {
			expect(flagController.getPostIdFromInputName('question', 'q_doflag')).to.be.undefined;

			expect(flagController.getPostIdFromInputName('answer', 'a1_doflag')).to.equal('1');
			expect(flagController.getPostIdFromInputName('answer', 'a11_doflag')).to.equal('11');
			expect(flagController.getPostIdFromInputName('answer', 'a111_doflag')).to.equal('111');

			expect(flagController.getPostIdFromInputName('comment', 'c2_doflag')).to.equal('2');
			expect(flagController.getPostIdFromInputName('comment', 'c23_doflag')).to.equal('23');
			expect(flagController.getPostIdFromInputName('comment', 'c234_doflag')).to.equal('234');

			expect(flagController.getPostIdFromInputName('doComment', 'a3_docomment')).to.equal('3');
			expect(flagController.getPostIdFromInputName('doComment', 'a34_docomment')).to.equal('34');
			expect(flagController.getPostIdFromInputName('doComment', 'a345_docomment')).to.equal('345');
		});
	});

	describe('recognizeInputKindByName()', () => {
		let flagController = {};

		before(() => {
			flagController = new FlagController();
		});

		after(() => {
			flagController = null;
		});

		it('should return correctly recognized input kind', () => {
			expect(flagController.recognizeInputKindByName('q_doflag')).to.equal('question');

			expect(flagController.recognizeInputKindByName('a1_doflag')).to.equal('answer');
			expect(flagController.recognizeInputKindByName('a11_doflag')).to.equal('answer');
			expect(flagController.recognizeInputKindByName('a111_doflag')).to.equal('answer');

			expect(flagController.recognizeInputKindByName('c2_doflag')).to.equal('comment');
			expect(flagController.recognizeInputKindByName('c23_doflag')).to.equal('comment');
			expect(flagController.recognizeInputKindByName('c234_doflag')).to.equal('comment');

			expect(flagController.recognizeInputKindByName('a3_docomment')).to.equal('doComment');
			expect(flagController.recognizeInputKindByName('a34_docomment')).to.equal('doComment');
			expect(flagController.recognizeInputKindByName('a345_docomment')).to.equal('doComment');
		});
	});

	describe('collectForumPostMetaData', () => {
		let flagController = {};
		let baseFormDOMStructure = null;

		before(() => {
			flagController = new FlagController();
			baseFormDOMStructure = createBasicFormStructure();
		});

		after(() => {
			clearPageBody();
			flagController = null;
		});

		it('should return object with forum post meta data', () => {
			flagController.flagButtonDOM = baseFormDOMStructure.elements.q_doflag;

			expect(flagController.collectForumPostMetaData()).to.include({
				questionId: '1',
				postType: 'q',
				postId: '1',
				relativeParentPostId: '1',
				code: '1-2345-6789-0',
			});
		});

		function createBasicFormStructure() {
			const form = document.createElement('form');
			form.action = 'question/1';

			const flagButton = document.createElement('input');
			flagButton.name = 'q_doflag';

			const secretCodeElement = document.createElement('input');
			secretCodeElement.name = 'code';
			secretCodeElement.value = '1-2345-6789-0';

			form.append(flagButton, secretCodeElement);

			return form;
		}
	});

	describe('getPostParentId()', () => {
		let flagController = {};

		before(() => {
			flagController = new FlagController();
		});

		after(() => {
			flagController = null;
		});

		it('should return null when postType is not "c"', () => {
			expect(flagController.getPostParentId('cc', null)).to.be.null;
		});

		it('should return null when parentElement ID is not a number', () => {
			const flagButtonDOM = document.createElement('input');

			expect(flagController.getPostParentId('c', flagButtonDOM)).to.be.null;
		});

		it('should return extracted number from parentElement ID', () => {
			const flagButtonParentDOM = document.createElement('div');
			flagButtonParentDOM.id = 'some-1234_list';
			const flagButtonDOM = document.createElement('input');

			flagButtonParentDOM.appendChild(flagButtonDOM);

			expect(flagController.getPostParentId('c', flagButtonDOM)).to.equal('1234');
		});
	});

	describe('swapFlagBtn()', () => {
		let flagController = {};

		before(() => {
			flagController = new FlagController();
		});

		after(() => {
			flagController = null;
		});

		it('should replace referenced button with a new button based on passed HTML code', () => {
			const referenceBtnParent = document.createElement('div');
			const referenceBtn = document.createElement('button');
			referenceBtnParent.appendChild(referenceBtn);

			const newBtnHTML = '<button id="testBtn">Test</button>';

			flagController.swapFlagBtn(referenceBtn, newBtnHTML);

			expect(referenceBtnParent.contains(referenceBtn)).to.be.false;
			expect(referenceBtnParent.firstElementChild.outerHTML).to.equal(newBtnHTML);
		});
	});

	describe('updateCurrentPostFlags()', () => {
		let flagController = {};

		before(() => {
			global.DOMParser = window.DOMParser;
			flagController = new FlagController();
		});

		afterEach(() => {
			clearPageBody();
		});

		after(() => {
			global.DOMParser = null;
			flagController = null;
		});

		it('should return false when there are no new flags', () => {
			const { postType, postId } = createBasicFlagsDOMStructure();
			const newFlagsHTML = '';

			expect(flagController.updateCurrentPostFlags(newFlagsHTML, { postId, postType })).to.be.false;
		});

		it('should return true when there are new flags', () => {
			const { postType, postId } = createBasicFlagsDOMStructure();
			const newFlagsHTML = `
                <span class="qa-q-view-flags">
                    1 zgłoszenie
                    <span class="qa-q-view-flags-pad">
                        <ul class="qa-item-flag-reason-list">
                            <li class="qa-item-reason-list-entry">
                                <span class="qa-item-flag-reason-prefix">
                                    Przez 
                                    <a href="/user/admin" class="qa-item-flag-reason-author">admin</a>
                                    , z powodu: 
                                </span>
                                <strong class="qa-item-flag-reason-item">
                                    Kod nie jest umieszczony w odpowiednim bloczku
                                </strong>
                            </li>
                        </ul>
                    </span>
                </span>
            `;

			flagController.wrapPostFlagReasons = spy(() => {});

			expect(flagController.updateCurrentPostFlags(newFlagsHTML, { postId, postType })).to.be.true;
			expect(flagController.wrapPostFlagReasons.calledWith(true)).to.be.true;
		});
	});

	describe('prepareFlagsUpdate()', () => {
		let flagController = {};

		before(() => {
			global.DOMParser = window.DOMParser;
			flagController = new FlagController();
		});

		afterEach(() => {
			clearPageBody();
		});

		after(() => {
			global.DOMParser = null;
			flagController = null;
		});

		it('should return object with flags property containing DOM parsed flags', () => {
			const { postType, postId } = createBasicFlagsDOMStructure();
			const flagsHTML = `
                <span class="qa-q-view-flags">
                    1 zgłoszenie
                    <span class="qa-q-view-flags-pad">
                        <ul class="qa-item-flag-reason-list">
                            <li class="qa-item-reason-list-entry">
                                <span class="qa-item-flag-reason-prefix">
                                    Przez 
                                    <a href="/user/admin" class="qa-item-flag-reason-author">admin</a>
                                    , z powodu: 
                                </span>
                                <strong class="qa-item-flag-reason-item">
                                    Kod nie jest umieszczony w odpowiednim bloczku
                                </strong>
                            </li>
                        </ul>
                    </span>
                </span>
            `;
			const preparedFlagsUpdate = flagController.prepareFlagsUpdate(flagsHTML, { postId, postType });

			expect(preparedFlagsUpdate).to.have.a.property('flags').that.have.a.property('outerHTML');
			expect(normalizeHTML(preparedFlagsUpdate.flags.outerHTML)).to.equals(normalizeHTML(flagsHTML));
		});

		it('should return object with flags property being null when flagsHTML parameter is empty string', () => {
			const { postType, postId } = createBasicFlagsDOMStructure();
			const flagsHTML = '';

			expect(flagController.prepareFlagsUpdate(flagsHTML, { postId, postType })).to.have.property('flags').that.is
				.null;
		});

		it('should return object with flagsAlreadyExist property being true when related post already has listed flags', () => {
			const { postType, postId } = createBasicFlagsDOMStructure({ shouldCreatePostFlagsWrapper: true });
			const flagsHTML = '';

			expect(flagController.prepareFlagsUpdate(flagsHTML, { postId, postType })).to.have.property(
				'flagsAlreadyExist'
			).that.is.true;
		});

		it('should return object with flagsAlternatePlace property being DOM object having descendant with className suffixed with "-who"', () => {
			const { postType, postId, flagsAlternatePlace } = createBasicFlagsDOMStructure({
				shouldHaveAlternateFlagsPlace: true,
			});
			const flagsHTML = '';

			expect(flagController.prepareFlagsUpdate(flagsHTML, { postId, postType }))
				.to.have.property('flagsAlternatePlace')
				.that.equals(flagsAlternatePlace);
		});
	});
});

function clearPageBody() {
	const parent = document.body;
	while (parent.lastChild) {
		parent.lastChild.remove();
	}
}

function createBasicFlagsDOMStructure({ shouldCreatePostFlagsWrapper, shouldHaveAlternateFlagsPlace } = {}) {
	const returnValue = {
		postType: 'q',
		postId: 1,
	};

	const flagsWrapperDOM = document.createElement('div');
	flagsWrapperDOM.id = `${returnValue.postType}${returnValue.postId}`;

	let flagsParentDOM = document.createElement('div');
	flagsParentDOM.classList.add(`qa-${returnValue.postType}-view-meta`);

	if (shouldCreatePostFlagsWrapper) {
		const postFlagsWrapper = document.createElement('div');
		postFlagsWrapper.classList.add(`qa-${returnValue.postType}-view-flags`);

		flagsParentDOM = postFlagsWrapper;
	}

	if (shouldHaveAlternateFlagsPlace) {
		const flagsAlternatePlace = document.createElement('div');
		flagsAlternatePlace.classList.add('.qa-q-view-who');

		returnValue.flagsAlternatePlace = flagsAlternatePlace;

		flagsParentDOM.appendChild(flagsAlternatePlace);
	}

	flagsWrapperDOM.appendChild(flagsParentDOM);
	document.body.appendChild(flagsWrapperDOM);

	return returnValue;
}

function normalizeHTML(html) {
	return html.replace(/\s{2,}|\t|\n|\r/g, '');
}
