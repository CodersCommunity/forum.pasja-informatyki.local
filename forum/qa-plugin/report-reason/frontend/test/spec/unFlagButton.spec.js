const { expect } = require('chai');
const { default: getUnFlagButtonHTML } = require('../cjs-src/unFlagButton');

const defaultParams = Object.freeze({
	questionId: 1,
	postId: 2,
	parentId: 3,
});

const normalizeHTML = (html) => html.replace(/\s{2,}|\t|\n|\r/g, '');

describe('getUnFlagButtonHTML()', () => {
	it('should return button for question postType', () => {
		const expected = `<input name="q_dounflag"
                    onclick="qa_show_waiting_after(this, false)"
                    value="wycofaj zgłoszenie"
                    title="Wycofaj zgłoszenie tej treści"
                    type="submit" 
                    class="qa-form-light-button qa-form-light-button-unflag">`;

		expect(
			normalizeHTML(
				getUnFlagButtonHTML({
					...defaultParams,
					postType: 'q',
				})
			)
		).to.equal(normalizeHTML(expected));
	});

	it('should return button for answer postType', () => {
		const expected = `<input name="a2_dounflag"
                    onclick="return qa_answer_click(2, 1, this);"
                    value="wycofaj zgłoszenie"
                    title="Wycofaj zgłoszenie tej treści"
                    type="submit" 
                    class="qa-form-light-button qa-form-light-button-unflag">`;

		expect(
			normalizeHTML(
				getUnFlagButtonHTML({
					...defaultParams,
					postType: 'a',
				})
			)
		).to.equal(normalizeHTML(expected));
	});

	it('should return button for comment postType', () => {
		const expected = `<input name="c2_dounflag"
                    onclick="return qa_comment_click(2, 1, 3, this);"
                    value="wycofaj zgłoszenie"
                    title="Wycofaj zgłoszenie tej treści"
                    type="submit" 
                    class="qa-form-light-button qa-form-light-button-unflag">`;

		expect(
			normalizeHTML(
				getUnFlagButtonHTML({
					...defaultParams,
					postType: 'c',
				})
			)
		).to.equal(normalizeHTML(expected));
	});

	it('should throw error when at least one parameter is missing', () => {
		const expected = "Cannot destructure property `postType` of 'undefined' or 'null'";

		expect(getUnFlagButtonHTML).to.throw(Error, expected);
	});

	it('should throw error when postType is not recognized', () => {
		const expected = 'Unrecognized postType: x for questionId: 1 and postId: 2';

		expect(
			getUnFlagButtonHTML.bind(null, {
				...defaultParams,
				postType: 'x',
			})
		).to.throw(Error, expected);
	});
});
