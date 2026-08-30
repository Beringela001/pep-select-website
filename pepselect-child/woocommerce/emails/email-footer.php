<?php
/**
 * Pep Select WooCommerce email footer.
 *
 * This shared override is used by every standard WooCommerce HTML email,
 * including notifications whose message body still uses the core template.
 *
 * @package PepSelectChild
 * @version 10.4.0
 */

defined( 'ABSPATH' ) || exit;

$email = $email ?? null;
?>
							</div>
						</td>
					</tr>
				</table>
				<!-- End Content -->
			</td>
		</tr>
	</table>
	<!-- End Body -->
</td>
</tr>
</table>
</td>
</tr>
<tr>
	<td align="center" valign="top">
		<!-- Pep Select company footer -->
		<?php
		$email_footer_text = get_option( 'woocommerce_email_footer_text' );
		if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
			$text_transient   = get_transient( 'woocommerce_email_footer_text' );
			$email_footer_text = false !== $text_transient ? $text_transient : $email_footer_text;
		}
		if ( function_exists( 'WC' ) && WC()->mailer() ) {
			$email_footer_text = WC()->mailer()->replace_placeholders( $email_footer_text );
		}

		if ( function_exists( 'pepselect_child_email_company_footer_html' ) ) {
			echo pepselect_child_email_company_footer_html( wp_kses_post( (string) $email_footer_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
		<!-- End Pep Select company footer -->
	</td>
</tr>
</table>
</div>
</td>
<td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
</tr>
</table>
</body>
</html>
