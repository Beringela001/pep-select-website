const assert = require('assert');
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const read = (file) => fs.readFileSync(path.join(root, file), 'utf8');

const template = read('pepselect-child/page-about-us.php');
const aboutModule = read('pepselect-child/inc/about-page.php');
const seo = read('pepselect-child/inc/seo-semantics.php');
const performance = read('pepselect-child/inc/performance.php');
const setup = read('pepselect-child/inc/setup.php');
const css = read('pepselect-child/assets/css/about.css');
const header = read('pepselect-child/inc/header-preview.php');
const footer = read('pepselect-child/inc/footer-preview.php');

assert.ok(setup.includes("'/inc/about-page.php'"), 'About module is not loaded');
assert.ok(aboutModule.includes("add_filter( 'template_include', 'pepselect_child_about_template', 99 )"), 'Coded About template is not forced ahead of legacy page metadata');
assert.ok(aboutModule.includes("'/page-about-us.php'"), 'Coded About template path is missing');
assert.ok(aboutModule.includes("get_page_by_path( 'tesa-10', OBJECT, 'product' )"), 'About visual is not tied to the current Tesamorelin product');
assert.ok(aboutModule.includes("$product->get_image_id()"), 'About visual does not use the WooCommerce featured image');
assert.ok(aboutModule.includes("'/assets/css/about.css'"), 'About stylesheet is not conditionally loaded');

assert.strictEqual((template.match(/<h1\b/g) || []).length, 1, 'About page must render one H1');
assert.ok(template.includes('Research compounds with records you can review.'), 'About H1 is missing');
assert.ok(template.includes("wp_get_attachment_image("), 'About page does not render the current product attachment');
assert.ok(template.includes("'fetchpriority' => 'high'"), 'About hero image is missing LCP priority');
assert.ok(template.includes('Explore Compounds'), 'Primary catalog CTA is missing');
assert.ok(template.includes('Review COAs'), 'COA CTA is missing');
assert.ok(template.includes('independent laboratory testing before a compound is released for sale'), 'Confirmed testing policy is missing');
assert.ok(!template.includes('the_content()'), 'legacy page-builder content must not render');
assert.ok(!/Compounds verified|Third-party tested|100%|20\+/.test(template), 'Legacy counters or unsupported totals remain');

assert.ok(seo.includes('About Pep Select | Research Compounds & Batch COAs'), 'About SEO title is missing');
assert.ok(seo.includes('batch-specific COA records accessible'), 'About meta description is missing');
assert.ok(seo.includes("home_url( '/about-us/' )"), 'About canonical URL is missing');
assert.ok(seo.includes("$types[] = 'AboutPage'"), 'AboutPage schema type is missing');
assert.ok(!seo.includes('noindex, follow'), 'About page remains noindex');
assert.ok(!seo.includes('wpseo_exclude_from_sitemap_by_post_ids'), 'About page remains excluded from the sitemap');

assert.ok(performance.includes("|| is_page( 'about-us' );"), 'About is outside the coded performance boundary');
assert.ok(css.includes('@media (max-width: 767px)'), 'About mobile layout is missing');
assert.ok(css.includes('@media (prefers-reduced-motion: reduce)'), 'About reduced-motion handling is missing');
assert.ok(!header.includes("get_page_url( 'about-us'"), 'About was added to header navigation');
assert.ok(!footer.includes("get_page_url( 'about-us'"), 'About was added to footer navigation');

console.log('coded About page safeguards verified');
