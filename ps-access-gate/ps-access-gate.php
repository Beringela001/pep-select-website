<?php
/**
 * Plugin Name: PS Access Gate
 * Plugin URI:  https://pepselect.com
 * Description: Compliance-grade access verification gate for research-use-only sites: researcher type, checkbox confirmations, numbered attestation, versioned consent, FDA/503A/503B legal block, and timestamped consent recording with CSV export. Cache-safe, fully configurable.
 * Version:     2.1.3
 * Author:      PepSelect
 * License:     GPL-2.0+
 * Text Domain: ps-access-gate
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class PS_Access_Gate {

	const OPTION_KEY = 'psag_settings';
	const DB_VERSION = '1';

	/** Curated font choices: key => [ label, css stack, Google Fonts family or null ] */
	public static function fonts() {
		return array(
			'system'    => array( 'System default (sans-serif)', "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif", null ),
			'georgia'   => array( 'Georgia (serif)', "Georgia, 'Times New Roman', serif", null ),
			'times'     => array( 'Times New Roman (serif)', "'Times New Roman', Times, serif", null ),
			'palatino'  => array( 'Palatino (serif)', "Palatino, 'Palatino Linotype', 'Book Antiqua', serif", null ),
			'garamond'  => array( 'Garamond (serif)', "Garamond, 'EB Garamond', serif", null ),
			'arial'     => array( 'Arial (sans-serif)', 'Arial, Helvetica, sans-serif', null ),
			'helvetica' => array( 'Helvetica (sans-serif)', "'Helvetica Neue', Helvetica, Arial, sans-serif", null ),
			'verdana'   => array( 'Verdana (sans-serif)', 'Verdana, Geneva, sans-serif', null ),
			'trebuchet' => array( 'Trebuchet MS (sans-serif)', "'Trebuchet MS', 'Segoe UI', sans-serif", null ),
			'courier'   => array( 'Courier New (monospace)', "'Courier New', Courier, monospace", null ),
			'inter'     => array( 'Inter (Google)', "'Inter', sans-serif", 'Inter:wght@400;600;800' ),
			'jakarta'   => array( 'Plus Jakarta Sans (Google)', "'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif", 'Plus+Jakarta+Sans:wght@400;500;600;700' ),
			'ibmplex'   => array( 'IBM Plex Mono (Google)', "'IBM Plex Mono', 'SFMono-Regular', Consolas, 'Liberation Mono', monospace", 'IBM+Plex+Mono:wght@500;600' ),
			'spacegro'  => array( 'Space Grotesk (Google)', "'Space Grotesk', sans-serif", 'Space+Grotesk:wght@500;700' ),
			'montserr'  => array( 'Montserrat (Google)', "'Montserrat', sans-serif", 'Montserrat:wght@400;700' ),
			'poppins'   => array( 'Poppins (Google)', "'Poppins', sans-serif", 'Poppins:wght@400;700' ),
			'lato'      => array( 'Lato (Google)', "'Lato', sans-serif", 'Lato:wght@400;700' ),
			'playfair'  => array( 'Playfair Display (Google)', "'Playfair Display', serif", 'Playfair+Display:wght@500;700' ),
			'merriwea'  => array( 'Merriweather (Google)', "'Merriweather', serif", 'Merriweather:wght@400;700' ),
		);
	}

	/** Resolve a font setting (key / 'custom' / legacy raw stack) to [ css stack, google family|null ] */
	public static function resolve_font( $value, $custom ) {
		$fonts = self::fonts();
		if ( isset( $fonts[ $value ] ) ) {
			return array( $fonts[ $value ][1], $fonts[ $value ][2] );
		}
		if ( 'custom' === $value && '' !== trim( (string) $custom ) ) {
			return array( $custom, null );
		}
		if ( '' !== trim( (string) $value ) && 'custom' !== $value ) {
			return array( $value, null );
		}
		return array( $fonts['system'][1], null );
	}

	public static function defaults() {
		return array(
			'enabled'          => 1,
			'logo_url'         => '',
			'kicker'           => 'Research Gate',
			'heading'          => 'Researcher Verification',
			'heading_accent'   => '',
			'intro'            => 'Research materials are supplied to qualified researchers for in vitro laboratory use only.',

			// Researcher type dropdown
			'show_type'        => 1,
			'type_label'       => 'Researcher type',
			'type_options'     => "University / academic institution\nPrivate research laboratory\nBiotech / pharmaceutical company\nContract research organization (CRO)\nIndependent researcher",

			// Checkbox confirmations (one per line)
			'checkboxes'       => "I am at least <b>21 years of age</b>.\nI have read the attestation and <b>confirm each statement is true</b>.",

			// Numbered attestation (one per line), shown in a collapsible section
			'attest_label'     => 'Read the researcher attestation',
			'attest_items'     => "I am acquiring these materials as a qualified researcher, or on behalf of a research institution or laboratory.\nAll materials will be used <b>solely for in vitro laboratory research</b>.\nI will not administer these materials to humans or animals, or resell or redistribute them for such use.\nI understand these materials are <b>not drugs, dietary supplements, or medical devices</b>, and have not been evaluated by the U.S. Food and Drug Administration.\nI am solely responsible for lawful handling, storage, use, and disposal, and for compliance with all applicable law.\nThe information I have provided is accurate, and false information is grounds for cancellation and refusal of service.",
			'attest_open'      => 0,

			// Versioned consent
			'form_version'     => 'PS-RUO-' . gmdate( 'Y.m' ),

			// Legal / FDA block (Orbitrex-style bordered box under the button)
			'legal_box'        => "By proceeding, you confirm that you are 21 years of age or older and a qualified research professional. You acknowledge that all products sold on this site are intended strictly for laboratory research use and are not for human consumption, diagnosis, treatment, or prevention of any disease. By creating an account and/or placing an order, you agree to our <a href=\"/terms-conditions/\">Terms and Conditions</a>, including all use restrictions, and agree to indemnify and hold harmless the seller from any misuse of products. You further confirm that you are legally permitted to purchase and use these materials in your jurisdiction.\n\n<b>FDA Disclaimer:</b> The statements made within this website have not been evaluated by the U.S. Food and Drug Administration. The statements and the products of this company are not intended to diagnose, treat, cure or prevent any disease. This company is not a compounding pharmacy or chemical compounding facility as defined under 503A of the Federal Food, Drug, and Cosmetic Act, and is not an outsourcing facility as defined under 503B of the Federal Food, Drug, and Cosmetic Act. All products are sold for research, laboratory, or analytical purposes only, and are not for human consumption.",

			'button_label'     => 'Enter Site →',
			'remember_label'   => 'Remember me for 30 days',
			'remember_days'    => 30,
			'show_remember'    => 1,
			'footer_note'      => 'Research use only. Recorded for compliance.',
			'address_line'     => '',
			'copyright_line'   => '© ' . gmdate( 'Y' ) . ' — For Research Use Only. All Rights Reserved.',
			'exit_text'        => 'Not a researcher?',
			'exit_link_label'  => 'Exit',
			'exit_url'         => 'https://google.com',

			'cookie_name'      => 'psag_verified',
			'record_consent'   => 1,

			'bg_color'         => '#001D3A', // --pep-color-dark-navy
			'accent_color'     => '#002A53', // --pep-color-navy (primary CTA)
			'link_color'       => '#17A1CF', // --pep-color-cyan
			'card_bg'          => '#FFFFFF',
			'text_color'       => '#002A53', // navy: headings + bold emphasis
			'bubbles'          => 0,
			'hide_logged_in'   => 1,
			'font_heading'        => 'georgia',  // --pep-font-editorial
			'font_body'           => 'jakarta',  // --pep-font-interface
			'font_heading_custom' => '',
			'font_body_custom'    => '',
		);
	}

	public static function get_settings() {
		$saved = get_option( self::OPTION_KEY, array() );
		$settings = wp_parse_args( is_array( $saved ) ? $saved : array(), self::defaults() );

		// Existing installations may retain the former URL in the saved legal box.
		// Normalize it at render time so an upgrade fixes the live link without
		// overwriting any merchant-approved legal wording around it.
		if ( ! empty( $settings['legal_box'] ) ) {
			$settings['legal_box'] = str_replace( '/terms-of-service/', '/terms-conditions/', $settings['legal_box'] );
		}

		return $settings;
	}

	public static function init() {
		register_activation_hook( __FILE__, array( __CLASS__, 'install_table' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_install_table' ) );

		add_action( 'admin_menu',  array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init',  array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_assets' ) );
		add_action( 'wp_footer',   array( __CLASS__, 'render_gate' ), 99 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'action_links' ) );

		// Consent recording (AJAX, works for logged-out visitors)
		add_action( 'wp_ajax_psag_record', array( __CLASS__, 'ajax_record' ) );
		add_action( 'wp_ajax_nopriv_psag_record', array( __CLASS__, 'ajax_record' ) );

		// CSV export
		add_action( 'admin_post_psag_export', array( __CLASS__, 'export_csv' ) );

		// Auto-purge page caches whenever gate settings are saved
		add_action( 'update_option_' . self::OPTION_KEY, array( __CLASS__, 'purge_caches' ), 10, 0 );
		add_action( 'add_option_' . self::OPTION_KEY, array( __CLASS__, 'purge_caches' ), 10, 0 );
	}

	/* ---------------- Consent log storage ---------------- */

	public static function table_name() {
		global $wpdb;
		return $wpdb->prefix . 'psag_consents';
	}

	public static function install_table() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset = $wpdb->get_charset_collate();
		$table   = self::table_name();
		dbDelta( "CREATE TABLE {$table} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME NOT NULL,
			form_version VARCHAR(64) NOT NULL DEFAULT '',
			researcher_type VARCHAR(190) NOT NULL DEFAULT '',
			ip VARCHAR(64) NOT NULL DEFAULT '',
			user_agent VARCHAR(255) NOT NULL DEFAULT '',
			PRIMARY KEY (id),
			KEY created_at (created_at)
		) {$charset};" );
		update_option( 'psag_db_version', self::DB_VERSION );
	}

	public static function maybe_install_table() {
		if ( get_option( 'psag_db_version' ) !== self::DB_VERSION ) {
			self::install_table();
		}
	}

	public static function ajax_record() {
		$s = self::get_settings();
		if ( empty( $s['record_consent'] ) ) {
			wp_send_json_success( array( 'recorded' => false ) );
		}
		global $wpdb;
		$type    = substr( sanitize_text_field( wp_unslash( $_POST['rtype'] ?? '' ) ), 0, 190 );
		$version = substr( sanitize_text_field( wp_unslash( $_POST['fversion'] ?? '' ) ), 0, 64 );
		$ip      = substr( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) ), 0, 64 );
		$ua      = substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) ), 0, 255 );

		$wpdb->insert( self::table_name(), array(
			'created_at'      => current_time( 'mysql', true ),
			'form_version'    => $version,
			'researcher_type' => $type,
			'ip'              => $ip,
			'user_agent'      => $ua,
		), array( '%s', '%s', '%s', '%s', '%s' ) );

		wp_send_json_success( array( 'recorded' => true ) );
	}

	public static function export_csv() {
		if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
		check_admin_referer( 'psag_export' );
		global $wpdb;
		$table = self::table_name();
		$rows  = $wpdb->get_results( "SELECT created_at, form_version, researcher_type, ip, user_agent FROM {$table} ORDER BY id DESC", ARRAY_A );

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=access-gate-consents-' . gmdate( 'Ymd-His' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array( 'Timestamp (UTC)', 'Form Version', 'Researcher Type', 'IP', 'User Agent' ) );
		foreach ( (array) $rows as $r ) {
			fputcsv( $out, $r );
		}
		fclose( $out );
		exit;
	}

	/* ---------------- Cache purge ---------------- */

	public static function purge_caches() {
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		global $kinsta_cache;
		if ( isset( $kinsta_cache ) && is_object( $kinsta_cache )
			&& isset( $kinsta_cache->kinsta_cache_purge )
			&& method_exists( $kinsta_cache->kinsta_cache_purge, 'purge_complete_caches' ) ) {
			$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
		}
		wp_cache_flush();
	}

	public static function action_links( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=ps-access-gate' ) ) . '">Settings</a>' );
		return $links;
	}

	/* ---------------- Admin ---------------- */

	public static function admin_menu() {
		add_options_page( 'Access Gate', 'Access Gate', 'manage_options', 'ps-access-gate', array( __CLASS__, 'settings_page' ) );
	}

	public static function register_settings() {
		register_setting( 'psag_group', self::OPTION_KEY, array( __CLASS__, 'sanitize' ) );
	}

	public static function admin_assets( $hook ) {
		if ( 'settings_page_ps-access-gate' !== $hook ) return;
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		$js = <<<'JS'
jQuery(function($){
	$('.psag-color').wpColorPicker();
	$('.psag-font-select').on('change', function(){
		var t = $($(this).data('target'));
		if ($(this).val() === 'custom') { t.show(); } else { t.hide(); }
	});
	var frame;
	$('#psag-logo-btn').on('click', function(e){
		e.preventDefault();
		if (frame) { frame.open(); return; }
		frame = wp.media({
			title: 'Select gate logo',
			button: { text: 'Use this logo' },
			library: { type: 'image' },
			multiple: false
		});
		frame.on('select', function(){
			var att = frame.state().get('selection').first().toJSON();
			var url = (att.sizes && att.sizes.large) ? att.sizes.large.url : att.url;
			$('#psag-logo-url').val(url).trigger('change');
			$('#psag-logo-preview').attr('src', url).show();
			$('#psag-logo-remove').show();
		});
		frame.open();
	});
	$('#psag-logo-remove').on('click', function(e){
		e.preventDefault();
		$('#psag-logo-url').val('');
		$('#psag-logo-preview').hide();
		$(this).hide();
	});
});
JS;
		wp_add_inline_script( 'wp-color-picker', $js );
	}

	public static function sanitize( $in ) {
		$d   = self::defaults();
		$out = array();

		foreach ( array( 'enabled', 'show_remember', 'bubbles', 'hide_logged_in', 'show_type', 'attest_open', 'record_consent' ) as $flag ) {
			$out[ $flag ] = empty( $in[ $flag ] ) ? 0 : 1;
		}

		$out['logo_url'] = esc_url_raw( $in['logo_url'] ?? '' );
		$out['exit_url'] = esc_url_raw( $in['exit_url'] ?? $d['exit_url'] );

		foreach ( array( 'kicker', 'heading', 'heading_accent', 'type_label', 'attest_label', 'form_version',
			'button_label', 'remember_label', 'footer_note', 'address_line', 'copyright_line',
			'exit_text', 'exit_link_label' ) as $t ) {
			$out[ $t ] = sanitize_text_field( $in[ $t ] ?? $d[ $t ] );
		}

		$out['cookie_name'] = preg_replace( '/[^a-zA-Z0-9_]/', '', $in['cookie_name'] ?? $d['cookie_name'] );
		if ( '' === $out['cookie_name'] ) $out['cookie_name'] = $d['cookie_name'];

		$out['remember_days'] = max( 1, min( 365, intval( $in['remember_days'] ?? $d['remember_days'] ) ) );

		$allowed = array( 'b' => array(), 'strong' => array(), 'em' => array(), 'i' => array(), 'br' => array(),
			'a' => array( 'href' => array(), 'target' => array(), 'rel' => array() ) );
		foreach ( array( 'intro', 'checkboxes', 'attest_items', 'legal_box' ) as $rich ) {
			$out[ $rich ] = wp_kses( $in[ $rich ] ?? $d[ $rich ], $allowed );
		}

		$out['type_options'] = sanitize_textarea_field( $in['type_options'] ?? $d['type_options'] );

		foreach ( array( 'bg_color', 'accent_color', 'link_color', 'card_bg', 'text_color' ) as $c ) {
			$v = sanitize_hex_color( $in[ $c ] ?? '' );
			$out[ $c ] = $v ? $v : $d[ $c ];
		}

		$fonts = self::fonts();
		foreach ( array( 'font_heading', 'font_body' ) as $f ) {
			$v = sanitize_text_field( $in[ $f ] ?? '' );
			$out[ $f ] = ( isset( $fonts[ $v ] ) || 'custom' === $v ) ? $v : $d[ $f ];
			$out[ $f . '_custom' ] = sanitize_text_field( $in[ $f . '_custom' ] ?? '' );
		}

		return $out;
	}

	public static function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;
		$s = self::get_settings();
		$k = self::OPTION_KEY;

		global $wpdb;
		$table = self::table_name();
		$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
		$last  = $wpdb->get_results( "SELECT created_at, form_version, researcher_type FROM {$table} ORDER BY id DESC LIMIT 8", ARRAY_A );
		?>
		<div class="wrap">
			<h1>Access Gate</h1>
			<p>Full-screen verification gate shown before visitors can browse. All checkboxes must be checked (and a researcher type selected, if enabled) before the Enter button activates. Caches are purged automatically on save. <b>Change the Form Version to force all previously verified visitors to re-consent to the new terms.</b></p>

			<form method="post" action="options.php">
				<?php settings_fields( 'psag_group' ); ?>
				<table class="form-table" role="presentation">

					<tr><th colspan="2"><h2 style="margin-bottom:0;">General</h2></th></tr>
					<tr><th scope="row">Enable gate</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[enabled]" value="1" <?php checked( $s['enabled'], 1 ); ?>> Show the gate to visitors</label>
					</td></tr>
					<tr><th scope="row">Skip for logged-in users</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[hide_logged_in]" value="1" <?php checked( $s['hide_logged_in'], 1 ); ?>> Don't show the gate to logged-in users (recommended while testing)</label>
					</td></tr>
					<tr><th scope="row">Logo</th><td>
						<img id="psag-logo-preview" src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="" style="max-width:240px;max-height:80px;display:<?php echo $s['logo_url'] ? 'block' : 'none'; ?>;margin-bottom:10px;border:1px solid #dcdcde;border-radius:6px;padding:8px;background:#fff;">
						<input type="hidden" id="psag-logo-url" name="<?php echo esc_attr( $k ); ?>[logo_url]" value="<?php echo esc_attr( $s['logo_url'] ); ?>">
						<button type="button" class="button" id="psag-logo-btn">Select from Media Library</button>
						<button type="button" class="button" id="psag-logo-remove" style="display:<?php echo $s['logo_url'] ? 'inline-block' : 'none'; ?>;">Remove</button>
					</td></tr>
					<tr><th scope="row">Kicker (small text above heading)</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[kicker]" value="<?php echo esc_attr( $s['kicker'] ); ?>">
						<p class="description">Small uppercase label, e.g. "Research Gate". Leave empty to hide.</p>
					</td></tr>
					<tr><th scope="row">Heading</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[heading]" value="<?php echo esc_attr( $s['heading'] ); ?>">
						&nbsp; Accent word(s): <input type="text" name="<?php echo esc_attr( $k ); ?>[heading_accent]" value="<?php echo esc_attr( $s['heading_accent'] ); ?>">
					</td></tr>
					<tr><th scope="row">Intro text</th><td>
						<textarea class="large-text" rows="2" name="<?php echo esc_attr( $k ); ?>[intro]"><?php echo esc_textarea( $s['intro'] ); ?></textarea>
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Researcher Type</h2></th></tr>
					<tr><th scope="row">Researcher type dropdown</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[show_type]" value="1" <?php checked( $s['show_type'], 1 ); ?>> Require visitors to select their researcher type</label><br><br>
						Label: <input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[type_label]" value="<?php echo esc_attr( $s['type_label'] ); ?>"><br><br>
						<textarea class="large-text" rows="5" name="<?php echo esc_attr( $k ); ?>[type_options]"><?php echo esc_textarea( $s['type_options'] ); ?></textarea>
						<p class="description">One option per line. Selection is required and is stored with each recorded consent.</p>
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Confirmations &amp; Attestation</h2></th></tr>
					<tr><th scope="row">Checkboxes</th><td>
						<textarea class="large-text" rows="3" name="<?php echo esc_attr( $k ); ?>[checkboxes]"><?php echo esc_textarea( $s['checkboxes'] ); ?></textarea>
						<p class="description">One checkbox per line. Basic HTML allowed: &lt;b&gt;, &lt;em&gt;, &lt;a&gt;.</p>
					</td></tr>
					<tr><th scope="row">Attestation</th><td>
						Toggle label: <input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[attest_label]" value="<?php echo esc_attr( $s['attest_label'] ); ?>"><br><br>
						<textarea class="large-text" rows="7" name="<?php echo esc_attr( $k ); ?>[attest_items]"><?php echo esc_textarea( $s['attest_items'] ); ?></textarea>
						<p class="description">One numbered statement per line, shown in a collapsible section. Leave empty to hide the attestation entirely.</p>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[attest_open]" value="1" <?php checked( $s['attest_open'], 1 ); ?>> Expanded by default</label>
					</td></tr>
					<tr><th scope="row">Form version</th><td>
						<input type="text" name="<?php echo esc_attr( $k ); ?>[form_version]" value="<?php echo esc_attr( $s['form_version'] ); ?>">
						<p class="description">Shown under the attestation and stored with each recorded consent. <b>Changing this re-gates every previously verified visitor</b> — bump it whenever you update your terms.</p>
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Legal Block &amp; Footer</h2></th></tr>
					<tr><th scope="row">Legal / FDA disclaimer box</th><td>
						<textarea class="large-text" rows="8" name="<?php echo esc_attr( $k ); ?>[legal_box]"><?php echo esc_textarea( $s['legal_box'] ); ?></textarea>
						<p class="description">Shown in a bordered, scrollable box under the Enter button. Separate paragraphs with a blank line. Link your Terms of Service with &lt;a href="/terms-conditions/"&gt;Terms and Conditions&lt;/a&gt;. Leave empty to hide.</p>
					</td></tr>
					<tr><th scope="row">Footer note</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[footer_note]" value="<?php echo esc_attr( $s['footer_note'] ); ?>">
					</td></tr>
					<tr><th scope="row">Business address line</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[address_line]" value="<?php echo esc_attr( $s['address_line'] ); ?>" placeholder="123 Example Rd Suite 100, City, ST 00000">
						<p class="description">Payment processors check for a consistent physical business address. Leave empty to hide.</p>
					</td></tr>
					<tr><th scope="row">Copyright line</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[copyright_line]" value="<?php echo esc_attr( $s['copyright_line'] ); ?>">
					</td></tr>
					<tr><th scope="row">Exit link</th><td>
						Text: <input type="text" name="<?php echo esc_attr( $k ); ?>[exit_text]" value="<?php echo esc_attr( $s['exit_text'] ); ?>">
						&nbsp; Link label: <input type="text" style="width:100px" name="<?php echo esc_attr( $k ); ?>[exit_link_label]" value="<?php echo esc_attr( $s['exit_link_label'] ); ?>">
						&nbsp; URL: <input type="url" class="regular-text" name="<?php echo esc_attr( $k ); ?>[exit_url]" value="<?php echo esc_attr( $s['exit_url'] ); ?>">
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Button &amp; Remember Me</h2></th></tr>
					<tr><th scope="row">Button label</th><td>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[button_label]" value="<?php echo esc_attr( $s['button_label'] ); ?>">
					</td></tr>
					<tr><th scope="row">"Remember me" option</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[show_remember]" value="1" <?php checked( $s['show_remember'], 1 ); ?>> Show a "remember me" checkbox</label><br><br>
						<input type="text" class="regular-text" name="<?php echo esc_attr( $k ); ?>[remember_label]" value="<?php echo esc_attr( $s['remember_label'] ); ?>">
						&nbsp; Days: <input type="number" min="1" max="365" style="width:70px" name="<?php echo esc_attr( $k ); ?>[remember_days]" value="<?php echo esc_attr( $s['remember_days'] ); ?>">
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Design</h2></th></tr>
					<tr><th scope="row">Colors</th><td>
						<table role="presentation"><tbody>
							<tr><td style="padding:4px 16px 4px 0;">Backdrop (behind the card)</td>
								<td style="padding:4px 0;"><input type="text" class="psag-color" name="<?php echo esc_attr( $k ); ?>[bg_color]" value="<?php echo esc_attr( $s['bg_color'] ); ?>"></td></tr>
							<tr><td style="padding:4px 16px 4px 0;">Button (when active)</td>
								<td style="padding:4px 0;"><input type="text" class="psag-color" name="<?php echo esc_attr( $k ); ?>[accent_color]" value="<?php echo esc_attr( $s['accent_color'] ); ?>"></td></tr>
							<tr><td style="padding:4px 16px 4px 0;">Links &amp; accents</td>
								<td style="padding:4px 0;"><input type="text" class="psag-color" name="<?php echo esc_attr( $k ); ?>[link_color]" value="<?php echo esc_attr( $s['link_color'] ); ?>"></td></tr>
							<tr><td style="padding:4px 16px 4px 0;">Card background</td>
								<td style="padding:4px 0;"><input type="text" class="psag-color" name="<?php echo esc_attr( $k ); ?>[card_bg]" value="<?php echo esc_attr( $s['card_bg'] ); ?>"></td></tr>
							<tr><td style="padding:4px 16px 4px 0;">Text</td>
								<td style="padding:4px 0;"><input type="text" class="psag-color" name="<?php echo esc_attr( $k ); ?>[text_color]" value="<?php echo esc_attr( $s['text_color'] ); ?>"></td></tr>
						</tbody></table>
					</td></tr>
					<tr><th scope="row">Fonts</th><td>
						<?php
						$fonts = self::fonts();
						foreach ( array( 'font_heading' => 'Heading', 'font_body' => 'Body text' ) as $field => $label ) :
							$current    = $s[ $field ];
							$is_known   = isset( $fonts[ $current ] ) || 'custom' === $current;
							$select_val = $is_known ? $current : 'custom';
							$custom_val = $is_known ? $s[ $field . '_custom' ] : $current;
						?>
						<div style="margin-bottom:12px;">
							<label style="display:inline-block;min-width:80px;"><?php echo esc_html( $label ); ?>:</label>
							<select class="psag-font-select" name="<?php echo esc_attr( $k ); ?>[<?php echo esc_attr( $field ); ?>]" data-target="#psag-<?php echo esc_attr( $field ); ?>-custom">
								<optgroup label="Web-safe (no external loading)">
									<?php foreach ( $fonts as $key => $f ) { if ( null === $f[2] ) { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $select_val, $key ); ?>><?php echo esc_html( $f[0] ); ?></option>
									<?php } } ?>
								</optgroup>
								<optgroup label="Google Fonts (loaded when gate shows)">
									<?php foreach ( $fonts as $key => $f ) { if ( null !== $f[2] ) { ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $select_val, $key ); ?>><?php echo esc_html( $f[0] ); ?></option>
									<?php } } ?>
								</optgroup>
								<option value="custom" <?php selected( $select_val, 'custom' ); ?>>Custom CSS font stack…</option>
							</select>
							<input type="text" class="regular-text" id="psag-<?php echo esc_attr( $field ); ?>-custom"
								name="<?php echo esc_attr( $k ); ?>[<?php echo esc_attr( $field ); ?>_custom]"
								value="<?php echo esc_attr( $custom_val ); ?>"
								placeholder="e.g. 'My Font', Georgia, serif"
								style="display:<?php echo 'custom' === $select_val ? 'inline-block' : 'none'; ?>;margin-left:8px;">
						</div>
						<?php endforeach; ?>
					</td></tr>
					<tr><th scope="row">Animated bubbles</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[bubbles]" value="1" <?php checked( $s['bubbles'], 1 ); ?>> Floating bubble animation on the backdrop</label>
					</td></tr>

					<tr><th colspan="2"><h2 style="margin-bottom:0;">Compliance Recording</h2></th></tr>
					<tr><th scope="row">Record verifications</th><td>
						<label><input type="checkbox" name="<?php echo esc_attr( $k ); ?>[record_consent]" value="1" <?php checked( $s['record_consent'], 1 ); ?>> Store a timestamped record of each verification (form version, researcher type, IP, browser)</label>
						<p class="description">Useful evidence for payment processor underwriting: shows affirmative, versioned consent collection.</p>
					</td></tr>
					<tr><th scope="row">Cookie name</th><td>
						<input type="text" name="<?php echo esc_attr( $k ); ?>[cookie_name]" value="<?php echo esc_attr( $s['cookie_name'] ); ?>">
					</td></tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<hr>
			<h2>Recorded Verifications (<?php echo esc_html( number_format_i18n( $total ) ); ?> total)</h2>
			<p><a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=psag_export' ), 'psag_export' ) ); ?>">Download all as CSV</a></p>
			<?php if ( ! empty( $last ) ) : ?>
			<table class="widefat striped" style="max-width:760px;">
				<thead><tr><th>Timestamp (UTC)</th><th>Form Version</th><th>Researcher Type</th></tr></thead>
				<tbody>
					<?php foreach ( $last as $row ) : ?>
					<tr>
						<td><?php echo esc_html( $row['created_at'] ); ?></td>
						<td><?php echo esc_html( $row['form_version'] ); ?></td>
						<td><?php echo esc_html( $row['researcher_type'] ); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php else : ?>
			<p><em>No verifications recorded yet.</em></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/* ---------------- Front end ---------------- */

	public static function render_gate() {
		$s = self::get_settings();

		if ( empty( $s['enabled'] ) ) return;
		if ( ! empty( $s['hide_logged_in'] ) && is_user_logged_in() ) return;
		if ( is_admin() || wp_doing_ajax() ) return;

		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $s['checkboxes'] ) ) ) );
		if ( empty( $lines ) ) $lines = array( 'I confirm I meet the requirements to access this site.' );

		$types  = array_values( array_filter( array_map( 'trim', explode( "\n", $s['type_options'] ) ) ) );
		$attest = array_values( array_filter( array_map( 'trim', explode( "\n", $s['attest_items'] ) ) ) );

		$legal_paras = array_values( array_filter( array_map( 'trim', preg_split( '/\n\s*\n/', $s['legal_box'] ) ) ) );
		$logo_id     = ! empty( $s['logo_url'] ) ? attachment_url_to_postid( $s['logo_url'] ) : 0;

		$cookie      = $s['cookie_name'];
		$days        = (int) $s['remember_days'];
		$cookie_val  = preg_replace( '/[^a-zA-Z0-9._\-]/', '', $s['form_version'] );
		if ( '' === $cookie_val ) $cookie_val = '1';

		list( $heading_stack, $g1 ) = self::resolve_font( $s['font_heading'], $s['font_heading_custom'] );
		list( $body_stack, $g2 )    = self::resolve_font( $s['font_body'], $s['font_body_custom'] );
		$google = array_filter( array_unique( array( $g1, $g2 ) ) );
		if ( ! empty( $google ) ) {
			$url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $google ) . '&display=swap';
			echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
			echo '<link rel="stylesheet" href="' . esc_url( $url ) . '">';
		}

		$heading = esc_html( $s['heading'] );
		if ( '' !== trim( $s['heading_accent'] ) && false !== strpos( $s['heading'], $s['heading_accent'] ) ) {
			$heading = str_replace(
				esc_html( $s['heading_accent'] ),
				'<span class="psag-accent">' . esc_html( $s['heading_accent'] ) . '</span>',
				$heading
			);
		}

		$show_type = ! empty( $s['show_type'] ) && ! empty( $types );
		?>
		<style>
		#psag-gate{position:fixed;inset:0;z-index:9999999;display:flex;align-items:flex-start;justify-content:center;padding:28px 18px;background:<?php echo esc_attr( $s['bg_color'] ); ?>;overflow-y:auto;font-family:<?php echo esc_attr( $body_stack ); ?>;}
		html.psag-open,body.psag-open{overflow:hidden!important;}
		#psag-gate .psag-bubbles{position:fixed;inset:0;overflow:hidden;z-index:0;pointer-events:none;}
		#psag-gate .psag-bubble{position:absolute;bottom:-120px;border-radius:50%;background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.14);animation:psagRise linear infinite;}
		@keyframes psagRise{0%{transform:translateY(0);opacity:0;}10%{opacity:1;}90%{opacity:1;}100%{transform:translateY(-115vh) translateX(30px);opacity:0;}}
		@media (prefers-reduced-motion:reduce){#psag-gate .psag-bubble{animation:none;display:none;}}
		/* PepSelect design tokens: navy #002A53 / dark-navy #001D3A / cyan #17A1CF / ink #13283D / slate #5E6F80 / neutral #7A8793 / border #D7E1E9 / surface #F3F8FC */
		.psag-card{position:relative;z-index:2;width:100%;max-width:560px;background:<?php echo esc_attr( $s['card_bg'] ); ?>;color:<?php echo esc_attr( $s['text_color'] ); ?>;border-radius:24px;border-top:4px solid <?php echo esc_attr( $s['accent_color'] ); ?>;padding:36px 34px 26px;text-align:center;box-shadow:0 30px 80px rgba(0,29,58,.30);margin:auto 0;}
		.psag-logo{margin:0 0 16px;display:flex;justify-content:center;}
		.psag-logo img{width:100%;max-width:220px;height:auto;object-fit:contain;display:block;}
		.psag-kicker{font-size:11px;font-weight:700;letter-spacing:.28em;text-transform:uppercase;color:#7A8793;margin:0 0 10px;}
		.psag-title{font-family:<?php echo esc_attr( $heading_stack ); ?>;font-weight:700;font-size:26px;margin:0 0 10px;color:<?php echo esc_attr( $s['text_color'] ); ?>;}
		.psag-title .psag-accent{color:<?php echo esc_attr( $s['link_color'] ); ?>;}
		.psag-intro{margin:0 0 22px;font-size:14px;line-height:1.6;color:#5E6F80;}
		.psag-divider{border:none;border-top:1px solid #D7E1E9;margin:0 0 20px;}
		.psag-type{text-align:left;margin:0 0 18px;}
		.psag-type-label{display:block;font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#7A8793;margin:0 0 8px;}
		.psag-type select{width:100%;padding:13px 14px;font-size:15px;font-family:inherit;color:#13283D;background:#fff;border:1px solid #D7E1E9;border-radius:12px;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%237A8793' d='M6 8L0 0h12z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;background-size:10px;cursor:pointer;transition:border-color 180ms ease,box-shadow 180ms ease;}
		.psag-type select:focus{outline:none;border-color:<?php echo esc_attr( $s['link_color'] ); ?>;box-shadow:0 0 0 3px color-mix(in srgb,<?php echo esc_attr( $s['link_color'] ); ?> 18%,transparent);}
		.psag-check{display:flex;align-items:flex-start;gap:12px;text-align:left;border-top:1px solid #D7E1E9;padding:15px 2px;margin:0;cursor:pointer;}
		.psag-check input{margin-top:2px;width:19px;height:19px;accent-color:<?php echo esc_attr( $s['accent_color'] ); ?>;flex:0 0 auto;cursor:pointer;}
		.psag-check span{font-size:14px;line-height:1.55;color:#13283D;}
		.psag-check b{color:<?php echo esc_attr( $s['text_color'] ); ?>;}
		.psag-attest{text-align:left;padding:4px 2px 16px;border-bottom:1px solid #D7E1E9;margin:0 0 4px;}
		#psag-gate .psag-attest-toggle,
		#psag-gate .psag-attest-toggle:hover,
		#psag-gate .psag-attest-toggle:focus,
		#psag-gate .psag-attest-toggle:active{display:inline-flex;align-items:center;gap:6px;background:transparent !important;background-image:none !important;border:none !important;box-shadow:none !important;outline:none !important;padding:4px 8px;margin-left:-8px;border-radius:8px;font-family:inherit;font-size:11.5px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:<?php echo esc_attr( $s['link_color'] ); ?> !important;cursor:pointer;transition:background 180ms ease;}
		#psag-gate .psag-attest-toggle:hover{background:#E8F6FB !important;} /* --pep-color-cyan-soft */
		#psag-gate ::selection{background:#E8F6FB;color:#13283D;}
		.psag-attest-toggle .psag-chev{transition:transform 180ms ease;display:inline-block;}
		.psag-attest.open .psag-attest-toggle .psag-chev{transform:rotate(180deg);}
		.psag-attest-body{display:none;margin:14px 0 0;}
		.psag-attest.open .psag-attest-body{display:block;}
		.psag-attest ol{margin:0 0 12px 20px;padding:0;}
		.psag-attest ol li{font-size:13.5px;line-height:1.65;color:#13283D;margin:0 0 10px;}
		.psag-attest ol li b{color:<?php echo esc_attr( $s['text_color'] ); ?>;}
		.psag-version{font-family:'IBM Plex Mono','SFMono-Regular',Consolas,'Liberation Mono',monospace;font-size:10.5px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#7A8793;margin:0;}
		#psag-gate .psag-btn{width:100%;margin:20px 0 0;padding:16px 0;border:none;border-radius:12px;font-weight:700;font-size:13.5px;letter-spacing:.12em;text-transform:uppercase;color:#fff;background:#7A8793;cursor:not-allowed;transition:transform 180ms ease,box-shadow 180ms ease,background 180ms ease,filter 180ms ease;font-family:inherit;}
		#psag-gate .psag-btn.ready{background:<?php echo esc_attr( $s['accent_color'] ); ?>;cursor:pointer;}
		#psag-gate .psag-btn.ready:hover{transform:translateY(-2px);filter:brightness(.85);box-shadow:0 12px 26px rgba(0,42,83,.16);}
		.psag-remember{display:flex;align-items:center;justify-content:center;gap:8px;font-size:13px;margin:14px 0 0;color:#5E6F80;cursor:pointer;}
		.psag-remember input{width:16px;height:16px;accent-color:<?php echo esc_attr( $s['accent_color'] ); ?>;cursor:pointer;}
		.psag-note{margin:12px 0 0;font-size:11.5px;color:#7A8793;}
		.psag-exit{font-size:12.5px;color:#5E6F80;margin:6px 0 0;}
		.psag-exit a{color:<?php echo esc_attr( $s['text_color'] ); ?>;font-weight:600;cursor:pointer;text-decoration:underline;text-underline-offset:3px;}
		.psag-legal{text-align:left;background:#F3F8FC;border:1px solid #D7E1E9;border-radius:12px;padding:16px 18px;margin:18px 0 0;max-height:190px;overflow-y:auto;}
		.psag-legal p{font-size:12px;line-height:1.65;color:#5E6F80;margin:0 0 10px;}
		.psag-legal p:last-child{margin-bottom:0;}
		.psag-legal b{color:<?php echo esc_attr( $s['text_color'] ); ?>;}
		.psag-legal a{color:<?php echo esc_attr( $s['link_color'] ); ?>;font-weight:600;}
		.psag-address{margin:16px 0 0;font-size:12px;color:#5E6F80;}
		.psag-copy{margin:4px 0 0;font-size:12px;color:#5E6F80;}
		@media(max-width:480px){.psag-card{padding:28px 20px 22px;border-radius:20px;}.psag-title{font-size:22px;}}
		</style>

		<div id="psag-gate" role="dialog" aria-modal="true" aria-labelledby="psag-title" aria-describedby="psag-intro">
			<?php if ( ! empty( $s['bubbles'] ) ) : ?><div class="psag-bubbles" id="psagBubbles"></div><?php endif; ?>
			<div class="psag-card">
				<?php if ( ! empty( $s['logo_url'] ) ) : ?>
				<div class="psag-logo">
					<?php if ( $logo_id ) : ?>
						<?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 480px) 160px, 220px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core returns escaped attachment markup. ?>
					<?php else : ?>
						<img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" loading="eager" fetchpriority="high">
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if ( '' !== trim( $s['kicker'] ) ) : ?>
				<p class="psag-kicker"><?php echo esc_html( $s['kicker'] ); ?></p>
				<?php endif; ?>

				<h2 class="psag-title" id="psag-title"><?php echo $heading; // escaped above ?></h2>
				<p class="psag-intro" id="psag-intro"><?php echo wp_kses_post( $s['intro'] ); ?></p>
				<hr class="psag-divider">

				<?php if ( $show_type ) : ?>
				<div class="psag-type">
					<label class="psag-type-label" for="psagType"><?php echo esc_html( $s['type_label'] ); ?></label>
					<select id="psagType">
						<option value="" selected disabled>Select…</option>
						<?php foreach ( $types as $t ) : ?>
						<option value="<?php echo esc_attr( $t ); ?>"><?php echo esc_html( $t ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<?php foreach ( $lines as $line ) : ?>
				<label class="psag-check">
					<input type="checkbox" class="psag-cb">
					<span><?php echo wp_kses_post( $line ); ?></span>
				</label>
				<?php endforeach; ?>

				<?php if ( ! empty( $attest ) ) : ?>
				<div class="psag-attest<?php echo ! empty( $s['attest_open'] ) ? ' open' : ''; ?>" id="psagAttest">
					<button type="button" class="psag-attest-toggle" id="psagAttestToggle" aria-expanded="<?php echo ! empty( $s['attest_open'] ) ? 'true' : 'false'; ?>" aria-controls="psagAttestBody">
						<?php echo esc_html( $s['attest_label'] ); ?> <span class="psag-chev">⌃</span>
					</button>
					<div class="psag-attest-body" id="psagAttestBody">
						<ol>
							<?php foreach ( $attest as $item ) : ?>
							<li><?php echo wp_kses_post( $item ); ?></li>
							<?php endforeach; ?>
						</ol>
						<?php if ( '' !== trim( $s['form_version'] ) ) : ?>
						<p class="psag-version">Form version <?php echo esc_html( $s['form_version'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>

				<button type="button" class="psag-btn" id="psagEnter" disabled><?php echo esc_html( $s['button_label'] ); ?></button>

				<?php if ( ! empty( $s['show_remember'] ) ) : ?>
				<label class="psag-remember"><input type="checkbox" id="psagRemember"> <?php echo esc_html( $s['remember_label'] ); ?></label>
				<?php endif; ?>

				<?php if ( '' !== trim( $s['footer_note'] ) ) : ?>
				<p class="psag-note"><?php echo esc_html( $s['footer_note'] ); ?></p>
				<?php endif; ?>

				<p class="psag-exit"><?php echo esc_html( $s['exit_text'] ); ?> <a id="psagExit" href="<?php echo esc_url( $s['exit_url'] ); ?>"><?php echo esc_html( $s['exit_link_label'] ); ?></a></p>

				<?php if ( ! empty( $legal_paras ) ) : ?>
				<div class="psag-legal">
					<?php foreach ( $legal_paras as $p ) : ?>
					<p><?php echo wp_kses_post( $p ); ?></p>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<?php if ( '' !== trim( $s['address_line'] ) ) : ?>
				<p class="psag-address"><?php echo esc_html( $s['address_line'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== trim( $s['copyright_line'] ) ) : ?>
				<p class="psag-copy"><?php echo esc_html( $s['copyright_line'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<script>
		(function(){
			var gate = document.getElementById('psag-gate');
			if(!gate) return;
			var backgroundNodes = [];

			var COOKIE  = <?php echo wp_json_encode( $cookie ); ?>;
			var VERSION = <?php echo wp_json_encode( $cookie_val ); ?>;
			var DAYS    = <?php echo (int) $days; ?>;
			var AJAX    = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
			var RECORD  = <?php echo ! empty( $s['record_consent'] ) ? 'true' : 'false'; ?>;
			var NEED_TYPE = <?php echo $show_type ? 'true' : 'false'; ?>;

			// Cookie must exist AND match the current form version.
			function hasValidCookie(){
				var target = COOKIE + '=' + VERSION;
				return document.cookie.split('; ').some(function(c){ return c === target; });
			}
			function setCookie(days){
				var host = location.hostname.replace(/^www\./, '');
				var expires = '';
				if (days) {
					var d = new Date();
					d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = '; expires=' + d.toUTCString();
				}
				var secure = location.protocol === 'https:' ? '; Secure' : '';
				document.cookie = COOKIE + '=' + VERSION + '; path=/; domain=.' + host + expires + '; SameSite=Lax' + secure;
			}
			function closeGate(){
				gate.style.display = 'none';
				document.documentElement.classList.remove('psag-open');
				document.body.classList.remove('psag-open');
				backgroundNodes.forEach(function(item){
					if (item.hadInert) item.node.setAttribute('inert', '');
					else item.node.removeAttribute('inert');
					if (item.ariaHidden === null) item.node.removeAttribute('aria-hidden');
					else item.node.setAttribute('aria-hidden', item.ariaHidden);
				});
				var main = document.querySelector('main, [role="main"]');
				if (main) {
					var priorTabindex = main.getAttribute('tabindex');
					main.setAttribute('tabindex', '-1');
					main.focus();
					if (priorTabindex === null) main.removeAttribute('tabindex');
					else main.setAttribute('tabindex', priorTabindex);
				}
			}

			if (hasValidCookie()) { closeGate(); return; }

			window.addEventListener('pageshow', function(e){
				if (e.persisted && hasValidCookie()) { closeGate(); }
			});

			document.documentElement.classList.add('psag-open');
			document.body.classList.add('psag-open');
			Array.prototype.forEach.call(document.body.children, function(node){
				if (node === gate || 'SCRIPT' === node.tagName || 'STYLE' === node.tagName) return;
				backgroundNodes.push({
					node: node,
					hadInert: node.hasAttribute('inert'),
					ariaHidden: node.getAttribute('aria-hidden')
				});
				node.setAttribute('inert', '');
				node.setAttribute('aria-hidden', 'true');
			});

			var wrap = document.getElementById('psagBubbles');
			if (wrap) {
				for (var i = 0; i < 14; i++) {
					var b = document.createElement('span');
					b.className = 'psag-bubble';
					var size = Math.random() * 70 + 20;
					b.style.width = size + 'px'; b.style.height = size + 'px';
					b.style.left = (Math.random() * 100) + '%';
					b.style.animationDuration = (Math.random() * 14 + 12) + 's';
					b.style.animationDelay = (-Math.random() * 20) + 's';
					wrap.appendChild(b);
				}
			}

			// Attestation toggle
			var attest = document.getElementById('psagAttest');
			var attToggle = document.getElementById('psagAttestToggle');
			if (attest && attToggle) {
				attToggle.addEventListener('click', function(){
					var open = attest.classList.toggle('open');
					attToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				});
			}

			var cbs   = gate.querySelectorAll('.psag-cb');
			var enter = document.getElementById('psagEnter');
			var typeSel = document.getElementById('psagType');
			var focusableSelector = 'a[href],button:not([disabled]),input:not([disabled]),select:not([disabled]),textarea:not([disabled]),[tabindex]:not([tabindex="-1"])';

			gate.addEventListener('keydown', function(event){
				if ('Tab' !== event.key) return;
				var focusable = Array.prototype.filter.call(gate.querySelectorAll(focusableSelector), function(node){
					return node.offsetParent !== null;
				});
				if (!focusable.length) return;
				var first = focusable[0];
				var last = focusable[focusable.length - 1];
				if (event.shiftKey && document.activeElement === first) {
					event.preventDefault();
					last.focus();
				} else if (!event.shiftKey && document.activeElement === last) {
					event.preventDefault();
					first.focus();
				}
			});

			window.requestAnimationFrame(function(){
				var initialFocus = typeSel || cbs[0] || gate.querySelector(focusableSelector);
				if (initialFocus) initialFocus.focus();
			});

			function sync(){
				var ok = true;
				Array.prototype.forEach.call(cbs, function(cb){ if (!cb.checked) ok = false; });
				if (NEED_TYPE && typeSel && !typeSel.value) ok = false;
				enter.disabled = !ok;
				enter.classList.toggle('ready', ok);
			}
			Array.prototype.forEach.call(cbs, function(cb){ cb.addEventListener('change', sync); });
			if (typeSel) typeSel.addEventListener('change', sync);

			enter.addEventListener('click', function(){
				if (enter.disabled) return;
				var remember = document.getElementById('psagRemember');
				setCookie((remember && remember.checked) ? DAYS : 0);

				if (RECORD) {
					try {
						var data = new FormData();
						data.append('action', 'psag_record');
						data.append('rtype', (NEED_TYPE && typeSel) ? typeSel.value : '');
						data.append('fversion', VERSION);
						// keepalive so the request survives immediate navigation
						fetch(AJAX, { method: 'POST', body: data, keepalive: true }).catch(function(){});
					} catch(e) {}
				}
				closeGate();
			});

		})();
		</script>
		<?php
	}
}

PS_Access_Gate::init();
