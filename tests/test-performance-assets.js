const assert = require('assert');
const fs = require('fs');
const path = require('path');

const performanceFile = fs.readFileSync(
	path.join(__dirname, '..', 'pepselect-child', 'inc', 'performance.php'),
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

[
	'elementor-gf-roboto',
	'elementor-gf-robotoslab',
	'elementor-gf-plusjakartasans',
	'elementor-gf-ibmplexmono',
].forEach((handle) => assert.ok(performanceFile.includes(`'${handle}'`), `missing font consolidation for ${handle}`));

assert.ok(performanceFile.includes("wp_enqueue_style( 'pepselect-google-fonts'"));
assert.ok(performanceFile.includes("'testing' === get_post_field( 'post_name', $queried_id )"));
assert.ok(performanceFile.includes("'/testing' === untrailingslashit( (string) $request_path )"));
assert.ok(performanceFile.includes("implode( '|', $families )"));
assert.ok(performanceFile.includes("wp_register_style( 'pepselect-child-foundations', false"));
assert.ok(performanceFile.includes("wp_add_inline_style( 'pepselect-child-foundations', $combined_css )"));
assert.ok(performanceFile.includes('wp_deregister_style( $style_handle )'));
assert.ok(performanceFile.includes("add_filter( 'style_loader_tag', 'pepselect_child_filter_testing_unused_style_tags', 20, 2 )"));
assert.ok(performanceFile.includes("add_filter( 'wp_resource_hints', 'pepselect_child_font_resource_hints', 10, 2 )"));
assert.ok(performanceFile.includes("add_action( 'wp_print_styles', 'pepselect_child_optimize_seo_template_assets', 997 )"));
assert.ok(performanceFile.includes("add_action( 'wp_print_styles', 'pepselect_child_consolidate_google_fonts', 998 )"));
assert.ok(performanceFile.includes("add_action( 'wp_print_styles', 'pepselect_child_inline_shell_styles', 999 )"));
assert.ok(!performanceFile.includes("wp_dequeue_script( 'jquery'"));
assert.ok(!performanceFile.includes("wp_deregister_script( 'jquery'"));

console.log('performance asset safeguards verified');
