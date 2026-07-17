<?php
/**
 * Compact factual confidence strip.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$signals = array(
	__( 'Live catalog pricing', 'pepselect-child' ),
	__( 'Current stock visibility', 'pepselect-child' ),
	__( 'Available batch records', 'pepselect-child' ),
	__( 'Direct support', 'pepselect-child' ),
);
?>
<aside class="pepselect-home__confidence" aria-label="<?php esc_attr_e( 'Pep Select catalog features', 'pepselect-child' ); ?>">
	<div class="pepselect-home__inner">
		<ul>
			<?php foreach ( $signals as $signal ) : ?>
				<li><span aria-hidden="true"></span><?php echo esc_html( $signal ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</aside>
