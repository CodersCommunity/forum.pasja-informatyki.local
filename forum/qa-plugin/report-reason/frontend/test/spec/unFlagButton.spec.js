// global.FLAG_REASONS_METADATA = {};

// const index = require('../../dist/script');
// console.log('index: ', index);

const { expect } = require('chai');
const { default: getUnFlagButtonHTML } = require('../cjs-src/unFlagButton');

// console.log('getUnFlagButtonHTML ???', getUnFlagButtonHTML);

describe('getUnFlagButtonHTML', () => {
    it('should throw error when postType is not recognized', () => {
       expect(getUnFlagButtonHTML.bind(null, {})).to.throw(Error, 'Unrecognized postType: undefined for questionId: undefined and postId: undefined');
       // expect(typeof getUnFlagButtonHTML()).toBe('string');
    });
});
