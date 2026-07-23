<?php
/**
 * Renders any coded legal page from the single legal-content data file.
 *
 * Selected by the template_include filter in inc/legal-pages.php for the four
 * known slugs. All copy is escaped here on output; the data file stores it raw.
 * Documents with more than six sections gain a plain in-page table of contents
 * with matching heading anchors.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pep_legal = function_exists( 'pepselect_child_current_legal_document' ) ? pepselect_child_current_legal_document() : null;

if ( null === $pep_legal ) {
	// Should not happen (the filter guarantees a match), but never blank the page.
	get_header();
	get_footer();
	return;
}

$pep_body = isset( $pep_legal['body'] ) && is_array( $pep_legal['body'] ) ? $pep_legal['body'] : array();

// Anchor ids are the h2 ordinal, computed identically here and in the render
// loop below so the table of contents and the sections always agree.
$pep_headings = array();
$pep_h2_count = 0;
foreach ( $pep_body as $pep_block ) {
	if ( isset( $pep_block['type'], $pep_block['level'] ) && 'heading' === $pep_block['type'] && 2 === (int) $pep_block['level'] ) {
		$pep_h2_count++;
		$pep_headings[] = array(
			'id'   => 'section-' . $pep_h2_count,
			'text' => $pep_block['text'],
		);
	}
}

// Table of contents only for the longer documents (Terms has fourteen sections).
$pep_show_toc = count( $pep_headings ) > 6;

get_header();
?>
<main id="pepselect-legal-main" class="pepselect-legal" tabindex="-1">
	<article class="pepselect-legal__inner">
		<header class="pepselect-legal__header">
			<h1 class="pepselect-legal__eyebrow"><?php echo esc_html( $pep_legal['title'] ); ?></h1>
			<?php if ( ! empty( $pep_legal['last_updated'] ) ) : ?>
				<p class="pepselect-legal__updated">
					<?php
					printf(
						/* translators: %s: last-updated date. */
						esc_html__( 'Last updated: %s', 'pepselect-child' ),
						esc_html( $pep_legal['last_updated'] )
					);
					?>
				</p>
			<?php endif; ?>
		</header>

		<?php if ( $pep_show_toc ) : ?>
			<nav class="pepselect-legal__toc" aria-label="<?php esc_attr_e( 'On this page', 'pepselect-child' ); ?>">
				<p class="pepselect-legal__toc-title"><?php esc_html_e( 'On this page', 'pepselect-child' ); ?></p>
				<ol class="pepselect-legal__toc-list">
					<?php foreach ( $pep_headings as $pep_h ) : ?>
						<li><a href="#<?php echo esc_attr( $pep_h['id'] ); ?>"><?php echo esc_html( $pep_h['text'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
		<?php endif; ?>

		<div class="pepselect-legal__body">
			<?php
			$pep_render_h2 = 0;
			foreach ( $pep_body as $pep_block ) {
				if ( empty( $pep_block['type'] ) ) {
					continue;
				}

				switch ( $pep_block['type'] ) {
					case 'heading':
						$pep_level = isset( $pep_block['level'] ) ? (int) $pep_block['level'] : 2;

						if ( 2 === $pep_level ) {
							$pep_render_h2++;
							printf(
								'<h2 id="%1$s" class="pepselect-legal__section-title">%2$s</h2>',
								esc_attr( 'section-' . $pep_render_h2 ),
								esc_html( $pep_block['text'] )
							);
						} else {
							printf(
								'<h3 class="pepselect-legal__section-title pepselect-legal__section-title--sub">%s</h3>',
								esc_html( $pep_block['text'] )
							);
						}
						break;

					case 'paragraph':
						echo '<p>' . wp_kses_post( $pep_block['html'] ) . '</p>';
						break;

					case 'list':
						$pep_list  = ! empty( $pep_block['ordered'] ) ? 'ol' : 'ul';
						$pep_items = isset( $pep_block['items'] ) && is_array( $pep_block['items'] ) ? $pep_block['items'] : array();

						echo '<' . esc_html( $pep_list ) . ' class="pepselect-legal__list">';
						foreach ( $pep_items as $pep_item ) {
							echo '<li>' . wp_kses_post( $pep_item ) . '</li>';
						}
						echo '</' . esc_html( $pep_list ) . '>';
						break;
				}
			}
			?>
		</div>
	</article>
</main>
<?php
get_footer();
