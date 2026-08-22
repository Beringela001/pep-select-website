const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

const [originalArg, acceptedArg, outputArg] = process.argv.slice(2);

if (!originalArg || !acceptedArg || !outputArg) {
	throw new Error('Usage: node compose-tesamorelin-home-label.cjs <original> <accepted-edit> <output>');
}

const originalPath = path.resolve(originalArg);
const acceptedPath = path.resolve(acceptedArg);
const outputPath = path.resolve(outputArg);

const targetWidth = 1200;
const targetHeight = 900;

// The printed face of the left vial label in the approved homepage source.
// Everything outside this rectangle must remain sourced from the original asset.
const label = { left: 258, top: 386, width: 280, height: 343 };

async function build() {
	for (const inputPath of [originalPath, acceptedPath]) {
		if (!fs.existsSync(inputPath)) {
			throw new Error(`Missing input: ${inputPath}`);
		}
	}

	const original = sharp(originalPath).ensureAlpha();
	const metadata = await original.metadata();
	if (metadata.width !== targetWidth || metadata.height !== targetHeight) {
		throw new Error(`Unexpected original dimensions: ${metadata.width}x${metadata.height}`);
	}

	const accepted = await sharp(acceptedPath)
		.resize(targetWidth, targetHeight, { fit: 'fill' })
		.ensureAlpha()
		.png()
		.toBuffer();

	await sharp(accepted)
		.extract(label)
		.webp({ quality: 92, effort: 6, smartSubsample: true })
		.toFile(outputPath);

	const labelOverlay = await sharp(outputPath).ensureAlpha().png().toBuffer();
	const preview = await original
		.composite([{ input: labelOverlay, left: label.left, top: label.top, blend: 'over' }])
		.raw()
		.toBuffer();

	const originalRaw = await sharp(originalPath).ensureAlpha().raw().toBuffer();
	let outsideChangedBytes = 0;
	let insideChangedBytes = 0;

	for (let y = 0; y < targetHeight; y += 1) {
		for (let x = 0; x < targetWidth; x += 1) {
			const inside = x >= label.left && x < label.left + label.width && y >= label.top && y < label.top + label.height;
			const offset = (y * targetWidth + x) * 4;
			for (let channel = 0; channel < 4; channel += 1) {
				if (originalRaw[offset + channel] !== preview[offset + channel]) {
					if (inside) insideChangedBytes += 1;
					else outsideChangedBytes += 1;
				}
			}
		}
	}

	if (outsideChangedBytes !== 0) {
		throw new Error(`Safety check failed: ${outsideChangedBytes} bytes changed outside the left label region`);
	}
	if (insideChangedBytes === 0) {
		throw new Error('Safety check failed: the left label region did not change');
	}

	console.log(JSON.stringify({
		output: outputPath,
		dimensions: `${label.width}x${label.height}`,
		label,
		insideChangedBytes,
		outsideChangedBytes,
	}, null, 2));
}

build().catch((error) => {
	console.error(error.message);
	process.exit(1);
});
