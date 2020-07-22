const { transformFileAsync } = require('@babel/core');
const { existsSync, mkdirSync, readdirSync, writeFile } = require('fs');

const SRC_PREFIX = './src/';
const TARGET_PREFIX = './cjs-src/';

createFolderForTransformedSrcFiles();
transformSrcFiles();

function createFolderForTransformedSrcFiles() {
	if (!existsSync(TARGET_PREFIX)) {
		mkdirSync(TARGET_PREFIX);
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
	const fileNames = readdirSync(`.${SRC_PREFIX}`);
	const transformOptions = {
		plugins: ['@babel/plugin-transform-modules-commonjs'],
	};

	return fileNames.map((fileName) => {
		return new Promise((resolve, reject) => {
			transformFileAsync(`.${SRC_PREFIX}${fileName}`, transformOptions).then((result) =>
				saveTransformedFile(result.code, fileName, resolve, reject)
			);
		});
	});
}

function saveTransformedFile(code, fileName, resolve, reject) {
	writeFile(`${TARGET_PREFIX}${fileName}`, code, (err) => {
		if (err) {
			reject(err);
		} else {
			resolve();
		}
	});
}
