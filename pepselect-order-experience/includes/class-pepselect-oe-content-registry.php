<?php

defined( 'ABSPATH' ) || exit;

/** Approved, product-name keyed content used by the private order experience. */
final class PepSelect_OE_Content_Registry {
	/** @return array<string,array<string,mixed>> */
	public static function compounds(): array {
		return array(
			'glp-3 r'      => self::entry( array( 'Metabolic regulation through triple-hormone activity', 'Body-weight, appetite, and liver-fat biology' ), array( 'metabolism', 'body-composition', 'liver-fat', 'appetite' ) ),
			'glp-2 t'      => self::entry( array( 'GIP and GLP-1 receptor engagement', 'Body-weight and insulin-response biology' ), array( 'metabolism', 'body-composition', 'appetite', 'insulin-response' ) ),
			'glp-1 s'      => self::entry( array( 'GLP-1-driven metabolic regulation', 'Appetite and body-weight biology' ), array( 'metabolism', 'body-composition', 'appetite' ) ),
			'cagrilintide' => self::entry( array( 'Appetite and body-weight regulation', 'Amylin-receptor response' ), array( 'metabolism', 'body-composition', 'appetite' ) ),
			'tesamorelin'  => self::entry( array( 'Visceral-fat and body-composition research', 'Growth-hormone release and liver-fat biology' ), array( 'body-composition', 'liver-fat', 'growth-hormone' ) ),
			'mots-c'       => self::entry( array( 'Cellular energy and metabolic stress', 'Exercise-capacity biology' ), array( 'metabolism', 'cellular-energy', 'exercise', 'inflammation' ) ),
			'ss-31'        => self::entry( array( 'Mitochondrial energy production', 'Cellular resilience and aging' ), array( 'cellular-energy', 'mitochondria', 'aging' ) ),
			'nad+'         => self::entry( array( 'Cellular energy metabolism', 'Mitochondrial fitness and metabolic regulation' ), array( 'metabolism', 'cellular-energy', 'mitochondria', 'aging' ) ),
			'ghk-cu'       => self::entry( array( 'Skin and tissue renewal', 'Wound-healing and repair biology' ), array( 'skin-renewal', 'tissue-repair' ) ),
			'bpc-157'      => self::entry( array( 'Blood-vessel formation', 'Tissue repair and integrity' ), array( 'tissue-repair', 'blood-vessels' ) ),
			'tb-500'       => self::entry( array( 'Cell movement and repair', 'Tissue and blood-vessel formation' ), array( 'tissue-repair', 'blood-vessels' ) ),
			'glutathione'  => self::entry( array( 'Cellular defense against oxidative stress', 'Skin-pigmentation biology' ), array( 'oxidative-stress', 'skin-renewal' ) ),
			'kpv'          => self::entry( array( 'Intestinal inflammation and barrier research', 'Airway and epithelial inflammation' ), array( 'inflammation', 'barrier-integrity' ) ),
			'pt-141'       => self::entry( array( 'Melanocortin-receptor activity', 'Receptor-activation behavior' ), array( 'melanocortin' ) ),
		);
	}

	/** @return array<string,string> */
	public static function area_labels(): array {
		return array(
			'metabolism'       => 'metabolic regulation',
			'body-composition' => 'body-composition research',
			'liver-fat'        => 'liver-fat biology',
			'appetite'         => 'appetite-related biology',
			'insulin-response' => 'insulin-response biology',
			'growth-hormone'   => 'growth-hormone release',
			'cellular-energy'  => 'cellular-energy research',
			'exercise'         => 'exercise-capacity biology',
			'inflammation'     => 'inflammation research',
			'mitochondria'     => 'mitochondrial function',
			'aging'            => 'cellular aging',
			'skin-renewal'     => 'skin renewal',
			'tissue-repair'    => 'tissue repair',
			'blood-vessels'    => 'blood-vessel formation',
			'oxidative-stress' => 'oxidative-stress research',
			'barrier-integrity'=> 'barrier-integrity research',
			'melanocortin'     => 'melanocortin-receptor activity',
		);
	}

	public static function display_name( string $key ): string {
		$names = array(
			'glp-3 r' => 'GLP-3 R', 'glp-2 t' => 'GLP-2 T', 'glp-1 s' => 'GLP-1 S', 'cagrilintide' => 'Cagrilintide',
			'tesamorelin' => 'Tesamorelin', 'mots-c' => 'MOTS-C', 'ss-31' => 'SS-31', 'nad+' => 'NAD+', 'ghk-cu' => 'GHK-CU',
			'bpc-157' => 'BPC-157', 'tb-500' => 'TB-500', 'glutathione' => 'Glutathione', 'kpv' => 'KPV', 'pt-141' => 'PT-141',
		);
		return $names[ $key ] ?? $key;
	}

	/** @return array<string,mixed>|null */
	public static function for_name( string $name ): ?array {
		$key = self::normalize_name( $name );
		$all = self::compounds();
		return $all[ $key ] ?? null;
	}

	public static function normalize_name( string $name ): string {
		$name = html_entity_decode( wp_strip_all_tags( $name ), ENT_QUOTES, 'UTF-8' );
		$name = strtolower( str_replace( array( '–', '—', '‑' ), '-', $name ) );
		$name = preg_replace( '/\b\d+(?:\.\d+)?\s*(?:mcg|mg|g|ml)\b/i', '', $name );
		$name = preg_replace( '/\s+/', ' ', trim( (string) $name ) );
		$aliases = array(
			'retatrutide' => 'glp-3 r', 'glp3-r' => 'glp-3 r', 'glp-3 rt' => 'glp-3 r',
			'tirzepatide' => 'glp-2 t', 'glp2-t' => 'glp-2 t',
			'semaglutide' => 'glp-1 s', 'glp1-s' => 'glp-1 s',
			'mots-c' => 'mots-c', 'mots c' => 'mots-c', 'ghk cu' => 'ghk-cu', 'nad' => 'nad+',
		);
		return $aliases[ $name ] ?? $name;
	}

	/** @param string[] $bullets @param string[] $areas @return array<string,mixed> */
	private static function entry( array $bullets, array $areas ): array {
		return array( 'bullets' => $bullets, 'areas' => $areas );
	}
}
