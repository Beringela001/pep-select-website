const assert = require('assert');
const fs = require('fs');
const path = require('path');

const performanceFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'performance.php'),
	'utf8'
);
const headerPreviewFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'header-preview.php'),
	'utf8'
);
const footerPreviewFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'footer-preview.php'),
	'utf8'
);
const retirementFiles = [
	performanceFile,
	headerPreviewFile,
	footerPreviewFile,
	fs.readFileSync(path.join(__dirname, '..', 'pepselect-child', 'inc', 'archive-compounds.php'), 'utf8'),
	fs.readFileSync(path.join(__dirname, '..', 'pepselect-child', 'inc', 'single-product.php'), 'utf8'),
	fs.readFileSync(path.join(__dirname, '..', 'pepselect-child', 'assets', 'css', 'header.css'), 'utf8'),
	fs.readFileSync(path.join(__dirname, '..', 'pepselect-child', 'assets', 'css', 'footer.css'), 'utf8'),
	fs.readFileSync(path.join(__dirname, '..', 'pepselect-child', 'assets', 'css', 'checkout.css'), 'utf8'),
].join('\n');
const themeBootstrap = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'setup.php'),
	'utf8'
);
const themeMetadata = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'style.css'),
	'utf8'
);
const brandAssetDirectory = path.join(__dirname, '..', 'pepselect-child', 'assets', 'images', 'brand');
const accessGateLogo = path.join(__dirname, '..', 'ps-access-gate', 'assets', 'pep-select-logo.png');
const confettiLoaderFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'assets', 'js', 'confetti-loader.js'),
	'utf8'
);

[
	'ywpar-blocks-style',
	'ywdpd_owl',
	'yith_ywdpd_frontend',
	'cwginstock_frontend_css',
	'cwginstock_bootstrap',
	'select2',
	'woocommerce-general',
	'woocommerce-layout',
	'woocommerce-smallscreen',
	'pepselect-child-bisn-form',
].forEach((handle) => assert.ok(performanceFile.includes(`'${handle}'`), `missing Quality Archive cleanup for ${handle}`));

assert.ok(performanceFile.includes("'testing' === get_post_field( 'post_name', $queried_id )"));
assert.ok(performanceFile.includes("'/testing' === untrailingslashit( (string) $request_path )"));
assert.ok(performanceFile.includes("wp_register_style( 'pepselect-child-foundations', false"));
assert.ok(performanceFile.includes("wp_add_inline_style( 'pepselect-child-foundations', $combined_css )"));
assert.ok(performanceFile.includes('wp_deregister_style( $style_handle )'));
assert.ok(performanceFile.includes("add_filter( 'style_loader_tag', 'pepselect_child_filter_testing_unused_style_tags', 20, 2 )"));
assert.ok(performanceFile.includes("add_filter( 'wp_resource_hints', 'pepselect_child_font_resource_hints', 10, 2 )"));
assert.ok(performanceFile.includes("add_action( 'wp_print_styles', 'pepselect_child_optimize_seo_template_assets', 997 )"));
assert.ok(performanceFile.includes("add_action( 'wp_print_styles', 'pepselect_child_inline_shell_styles', 999 )"));
assert.ok(performanceFile.includes("add_filter( 'googlesitekit_adsense_tag_blocked', 'pepselect_child_block_unused_adsense_tag' )"));
assert.ok(performanceFile.includes("add_filter( 'googlesitekit_adsense_tag_amp_blocked', 'pepselect_child_block_unused_adsense_tag' )"));
assert.ok(!performanceFile.includes("googlesitekit_analytics_tag_blocked"));
assert.ok(!performanceFile.includes("googlesitekit_tagmanager_tag_blocked"));
[
	'pepselect_child_is_elementor_editor_request',
	'pepselect_child_is_legacy_shell_request',
	'pepselect_child_suppress_elementor_header_preview',
	'pepselect_child_suppress_elementor_footer_preview',
	'pepselect_child_block_elementor_single_product',
	'pepselect_child_remove_elementor_styles',
	'pepselect_child_remove_elementor_scripts',
	'pepselect_child_consolidate_google_fonts',
	'/plugins/elementor/',
	'/plugins/elementor-pro/',
	'/uploads/elementor/css/',
	'deensimc-marquee',
].forEach((legacyReference) => {
	assert.ok(!retirementFiles.includes(legacyReference), `obsolete Elementor compatibility remains: ${legacyReference}`);
});
assert.ok(themeMetadata.includes('Template: hello-elementor'), 'Hello Elementor must remain the parent theme');
assert.ok(themeBootstrap.includes("wp_get_theme( 'hello-elementor' )"), 'Hello Elementor availability guard must remain');
assert.ok(performanceFile.includes("array( 'hello-elementor' )"), 'Hello Elementor style dependency must remain');
[
	'jquery-blockui',
	'js-cookie',
	'underscore',
	'wp-util',
	'woocommerce',
	'hello-theme-frontend',
	'sourcebuster-js',
	'wc-order-attribution',
	'pepselect-cart-recovery',
].forEach((handle) => assert.ok(performanceFile.includes(`'${handle}'`), `missing defer strategy for ${handle}`));
assert.ok(!performanceFile.includes("wp_dequeue_script( 'jquery'"));
assert.ok(!performanceFile.includes("wp_deregister_script( 'jquery'"));
assert.ok(performanceFile.includes("$wp_scripts->registered['xoo-confetti']"));
assert.ok(performanceFile.includes("'/woocommerce-side-cart-premium/assets/library/confetti/'"));
assert.ok(performanceFile.includes("'/assets/js/confetti-loader.js'"));
assert.ok(performanceFile.includes("window.pepselectConfettiSource"));
assert.ok(confettiLoaderFile.includes("typeof window.confetti?.create === 'function'"));
assert.ok(confettiLoaderFile.includes('document.head.appendChild( script )'));
assert.ok(confettiLoaderFile.includes('proxy.create = async'));
assert.ok(headerPreviewFile.includes('pep-select-logo-header-448.webp'));
assert.ok(headerPreviewFile.includes('pep-select-logo-header-320.webp'));
assert.ok(headerPreviewFile.includes('srcset="%2$s 320w, %1$s 448w"'));
assert.ok(headerPreviewFile.includes('sizes="(max-width: 767px) 252px, 224px"'));
assert.ok(headerPreviewFile.includes('width="448" height="96"'));
assert.ok(footerPreviewFile.includes('pep-select-logo-footer-448.webp'));
assert.ok(footerPreviewFile.includes('width="448" height="95"'));
assert.ok(fs.statSync(path.join(brandAssetDirectory, 'pep-select-logo-header-448.webp')).size < 30000);
assert.ok(fs.statSync(path.join(brandAssetDirectory, 'pep-select-logo-header-320.webp')).size < 18000);
assert.ok(fs.statSync(path.join(brandAssetDirectory, 'pep-select-logo-footer-448.webp')).size < 20000);
assert.ok(fs.statSync(accessGateLogo).size < 25000);

console.log('performance asset safeguards verified');
