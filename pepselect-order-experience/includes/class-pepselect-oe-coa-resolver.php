<?php

defined( 'ABSPATH' ) || exit;

/** Resolves public COA facts and the exact photographed vial by batch number. */
final class PepSelect_OE_COA_Resolver {
	/** @return array<string,mixed> */
	public function resolve( string $batch_number ): array {
		$batch_number = trim( $batch_number );
		// Approved NAD label typo: keep historical Ops snapshots intact.
		if ( 'ND50026205JP' === strtoupper( $batch_number ) ) {
			$batch_number = 'ND50026205JS';
		}
		if ( '' === $batch_number || ! post_type_exists( 'ps_coa_test' ) ) {
			return array();
		}
		$ids = get_posts(
			array(
				'post_type'        => 'ps_coa_test',
				'post_status'      => 'publish',
				'posts_per_page'   => 2,
				'fields'           => 'ids',
				'no_found_rows'    => true,
				'suppress_filters' => false,
				'meta_query'       => array(
					array( 'key' => 'batch_number', 'value' => $batch_number, 'compare' => '=' ),
				),
			)
		);
		if ( 1 !== count( $ids ) ) {
			return array();
		}
		$post_id = absint( $ids[0] );
		if ( $this->truthy( get_post_meta( $post_id, '_ps_coa_private', true ) ) || 'private' === get_post_meta( $post_id, 'public_visibility', true ) ) {
			return array();
		}
		$stage   = sanitize_key( (string) get_post_meta( $post_id, 'workflow_stage', true ) );
		$outcome = sanitize_key( (string) get_post_meta( $post_id, 'coa_status', true ) );
		if ( ! in_array( $stage, array( 'in-testing', 'complete' ), true ) || ! in_array( $outcome, array( 'approved', 'failed', 'pending' ), true ) ) {
			return array();
		}
		$image_id = absint( get_post_meta( $post_id, 'batch_vial_photo', true ) );
		return array(
			'batch'           => $batch_number,
			'post_id'         => $post_id,
			'stage'           => $stage,
			'outcome'         => $outcome,
			'url'             => get_permalink( $post_id ) ?: '',
			'image_id'        => $image_id,
			'image'           => $image_id && wp_attachment_is_image( $image_id ) ? wp_get_attachment_image_url( $image_id, 'large' ) : '',
			'image_srcset'    => $image_id ? wp_get_attachment_image_srcset( $image_id, 'large' ) : '',
			'purity'          => 'complete' === $stage ? trim( (string) get_post_meta( $post_id, 'purity_percentage', true ) ) : '',
			'test_date'       => 'complete' === $stage ? trim( (string) get_post_meta( $post_id, 'test_date', true ) ) : '',
			'lab'             => $this->lab_name( $post_id ),
		);
	}

	private function lab_name( int $post_id ): string {
		$key = sanitize_key( (string) get_post_meta( $post_id, 'testing_lab', true ) );
		if ( 'other' === $key ) {
			return trim( (string) get_post_meta( $post_id, 'other_testing_lab', true ) );
		}
		$labels = array( 'ils-labs' => 'ILS Labs', 'freedom-labs' => 'Freedom Diagnostics', 'janoshik' => 'Janoshik' );
		return $labels[ $key ] ?? '';
	}

	private function truthy( mixed $value ): bool {
		return in_array( $value, array( true, 1, '1', 'true', 'yes', 'on' ), true );
	}
}
