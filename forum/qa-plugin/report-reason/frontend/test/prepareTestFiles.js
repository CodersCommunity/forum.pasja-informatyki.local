const { transformFileAsync } = require('@babel/core');
const { existsSync, mkdirSync, readdirSync, writeFile } = require('fs');
const { resolve } = require('path');

const SRC_PREFIX = '../src/';
const TARGET_PREFIX = './cjs-src/';

createFolderForTransformedSrcFiles();
transformSrcFiles();

function createFolderForTransformedSrcFiles() {
	const resolvedTargetRelativePath = resolveRelativePath(TARGET_PREFIX);

	if (!existsSync(resolvedTargetRelativePath)) {
		mkdirSync(resolvedTargetRelativePath);
	}
}

function transformSrcFiles() {
	Promise.all(getTransformations()).then(
		() => {
			console.log('Re-compiled ES Modules to CommonJS.');
		},
		(error) => {
			throw new Error(`Error while re-compiling from ES Modules to CommonJS: ${error}`);
		}
	);
}

function getTransformations() {
	const fileNames = readdirSync(resolveRelativePath(SRC_PREFIX));
	const transformOptions = {
		plugins: ['@babel/plugin-transform-modules-commonjs'],
		babelrc: false,
		/*
			Unit Tests test source files original syntax (ES6+), not their transpiled (ES5) form,
			so config from .babelrc file need to be ignored here.
		 */
	};

	return fileNames.map((fileName) => {
		return new Promise((resolve, reject) => {
			transformFileAsync(resolveRelativePath(SRC_PREFIX, fileName), transformOptions).then((result) =>
				saveTransformedFile(result.code, fileName, resolve, reject)
			);
		});
	});
}

function saveTransformedFile(code, fileName, resolve, reject) {
	writeFile(resolveRelativePath(TARGET_PREFIX, fileName), code, (err) => {
		if (err) {
			reject(err);
		} else {
			resolve();
		}
	});
}

function resolveRelativePath(...relativePaths) {
	return resolve(__dirname, ...relativePaths);
}
