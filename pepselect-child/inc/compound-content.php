<?php
/**
 * Compound content for single product pages (WEB-2E).
 *
 * Approved, mechanism-only descriptions in the hybrid reference register.
 * Keyed by normalized compound name so content survives product edits.
 * Specs and sources are supplied here as reviewed data; the template renders
 * them but invents nothing. Compounds absent from this map simply render the
 * standard WooCommerce description with the locked legal blocks.
 *
 * @package PepSelectChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the full compound-content library.
 *
 * @return array<string,array<string,mixed>>
 */
function pepselect_child_compound_library() {
	static $library = null;

	if ( null !== $library ) {
		return $library;
	}

	$library = array(
		'glutathione' => array(
			'description' => __( 'Glutathione is a tripeptide (glutamate&ndash;cysteine&ndash;glycine) studied as a principal cellular antioxidant. It is researched for its role as a redox buffer and as a cofactor in enzymatic detoxification and protein regulation.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '70-18-8', 'Formula' => 'C10H17N3O6S', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for cellular defense against oxidative stress', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for its role in skin pigmentation and brightness biology', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for redox control and signaling inside cells', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Lu SC. Biochim Biophys Acta. 2013. DOI:10.1016/j.bbagen.2012.10.008',
				'Weschawalit S, et al. Clin Cosmet Investig Dermatol. 2017. DOI:10.2147/CCID.S128278',
				'Allen EMG, Mieyal JJ. Antioxidants. 2023. [VERIFY DOI]',
			),
		),
		'pt-141' => array(
			'description' => __( 'PT-141 (bremelanotide) is a cyclic heptapeptide studied as a melanocortin receptor agonist. It is researched for its activity at central melanocortin receptors and the downstream signaling associated with them.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '189691-06-3', 'Formula' => 'C50H68N14O10', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for its activity at melanocortin receptors', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for melanocortin signaling in the nervous system', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for its receptor-activation behavior', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Pfaus JG, et al. Proc Natl Acad Sci USA. 2004. DOI:10.1073/pnas.0400491101',
				'Pfaus J, Giuliano F, Gelez H. J Sex Med. 2007. DOI:10.1111/j.1743-6109.2007.00610.x',
				'Diamond LE, et al. Curr Top Med Chem. 2007. DOI:10.2174/156802607780906681',
			),
		),
		'glp-3 r' => array(
			'description' => __( 'Retatrutide is a synthetic peptide studied as a triple receptor agonist, engineered to engage the GLP-1, GIP, and glucagon receptors. It is researched for the structural basis of its simultaneous activity across all three receptors.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '2381089-83-2', 'Formula' => 'C221H342N46O68', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for metabolic regulation through triple-hormone signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for body-weight and appetite-related biology', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Investigated for liver-fat and cardiometabolic pathways', 'pepselect-child' ), 'ref' => 2 ),
			),
			'sources'     => array(
				'Jastreboff AM, et al. N Engl J Med. 2023. DOI:10.1056/NEJMoa2301972',
				'Retatrutide MASLD phase 2a. Nat Med. 2024. DOI:10.1038/s41591-024-03018-2',
			),
		),
		'ghk-cu' => array(
			'description' => __( 'GHK-Cu is a copper-binding tripeptide (glycyl-L-histidyl-L-lysine) studied for its role in tissue remodeling. It is researched for stimulating structural-protein synthesis and modulating genes tied to skin and matrix regeneration.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '300801-03-0', 'Formula' => 'C28H48CuN12O8', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for skin and tissue renewal', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for wound-healing and repair signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Investigated for regenerative signaling pathways in skin', 'pepselect-child' ), 'ref' => 2 ),
			),
			'sources'     => array(
				'Pickart L, Margolina A. J Biomater Sci Polym Ed. 2008. PMID:18644225',
				'Pickart L, et al. Oxid Med Cell Longev. 2015. DOI:10.1155/2015/648108',
			),
		),
		'nad+' => array(
			'description' => __( 'NAD+ (nicotinamide adenine dinucleotide) is a coenzyme studied for its central role in cellular energy metabolism. It is researched as a substrate in redox reactions and as a driver of sirtuin-dependent regulatory pathways.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '53-84-9', 'Formula' => 'C21H27N7O14P2', 'Form' => 'Solid' ),
			'context'     => array(
				array( 'text' => __( 'Studied for its role in cellular energy metabolism', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for mitochondrial fitness and sirtuin-linked signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Investigated for system-wide metabolic regulation', 'pepselect-child' ), 'ref' => 1 ),
			),
			'sources'     => array(
				'Canto C, Menzies KJ, Auwerx J. Cell Metab. 2015. [VERIFY DOI]',
			),
		),
		'ss-31' => array(
			'description' => __( 'SS-31 (elamipretide) is a mitochondria-targeted tetrapeptide studied for its ability to bind cardiolipin in the inner mitochondrial membrane. It is researched for its role in supporting mitochondrial energy production and cellular resilience.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '736992-21-5', 'Formula' => 'C32H49N9O5', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for mitochondrial membrane protection and energy production', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for mitochondrial function in aging', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for brain-energy and mitochondrial stress', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Chavez JD, et al. Proc Natl Acad Sci USA. 2020. DOI:10.1073/pnas.2002250117',
				'Pharaoh G, et al. J Physiol. 2023. PMID:37462785',
				'Wu J, et al. Mol Neurobiol. 2019. [VERIFY DOI]',
			),
		),
		'glp-1 s' => array(
			'description' => __( 'Semaglutide is a GLP-1 receptor agonist studied for its engagement of the GLP-1 receptor. It is researched for the signaling pathways it activates and for the prolonged receptor occupancy associated with its structure.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '910463-68-2', 'Formula' => 'C187H291N45O59', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for GLP-1-driven metabolic regulation', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for appetite and body-weight biology', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for oral peptide delivery in metabolic research', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Kommu S, et al. StatPearls. 2024.',
				'Salvador R, et al. Cureus. 2025. PMID:40143174',
				'Yang F, et al. Diabetes Res Clin Pract. 2021. DOI:10.1016/j.diabres.2021.108656',
			),
		),
		'tesamorelin' => array(
			'description' => __( 'Tesamorelin is a growth hormone-releasing hormone (GHRH) analogue studied for its action on pituitary somatotroph cells. It is researched for its role in stimulating endogenous growth hormone release through GHRH-receptor signaling.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '218949-48-5', 'Formula' => 'C221H366N72O67S', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for visceral-fat and body-composition biology', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for growth-hormone-releasing signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Investigated for liver-fat and body-composition research', 'pepselect-child' ), 'ref' => 2 ),
			),
			'sources'     => array(
				'Bedimo R, et al. HIV Ther. 2011. PMID:22096409',
				'Tesamorelin body-composition meta-analysis. 2025. PMID:41545261',
			),
		),
		'tb-500' => array(
			'description' => __( 'TB-500 is a synthetic peptide derived from thymosin beta-4 studied as an actin-sequestering molecule. It is researched for its role in regulating actin filament assembly and cell motility.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '[SUPPLY FROM COA]', 'Formula' => '[SUPPLY FROM COA]', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for its role in cell movement and repair signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for regulating actin, a key structural protein', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for its role in tissue and blood-vessel formation', 'pepselect-child' ), 'ref' => 1 ),
			),
			'sources'     => array(
				'Goldstein AL, et al. Trends Mol Med. 2005. DOI:10.1016/j.molmed.2005.07.004',
				'Yu FX, et al. J Biol Chem. 1993. PMID:8416954',
				'Safer D, et al. PMID:8471179',
			),
		),
		'glp-2 t' => array(
			'description' => __( 'Tirzepatide is a synthetic peptide studied as a dual receptor agonist engaging both the GIP and GLP-1 receptors. It is researched for the structural basis of its balanced activity across the two incretin receptors.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '2023788-19-2', 'Formula' => 'C225H348N48O68', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for dual-incretin metabolic regulation', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for blood-sugar and insulin-response biology', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for body-weight regulation through dual signaling', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Nauck MA, et al. Cardiovasc Diabetol. 2022. DOI:10.1186/s12933-022-01604-7',
				'Rosenstock J, et al. Lancet. 2021. PMID:34186022',
				'Kassab HK, et al. Int J Obes. 2023. DOI:10.1038/s41366-023-01337-x',
			),
		),
		'mots-c' => array(
			'description' => __( 'MOTS-c is a mitochondrial-derived peptide encoded within the mitochondrial 12S rRNA region, studied as a mitochondrial-to-nuclear signaling molecule. It is researched for its role in metabolic regulation through the AMPK pathway.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '1627580-64-6', 'Formula' => 'C101H152N28O22S2', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for cellular energy and metabolic stress signaling', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for exercise capacity and physical-performance biology', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for inflammation-linked metabolic pathways', 'pepselect-child' ), 'ref' => 3 ),
			),
			'sources'     => array(
				'Kong BS, et al. Diabetes Metab J. 2023. [VERIFY DOI]',
				'Mohtashami Z, et al. Int J Mol Sci. 2022.',
				'Zheng Y, et al. Front Endocrinol. 2023. [VERIFY DOI]',
			),
		),
		'bpc-157' => array(
			'description' => __( 'BPC-157 is a pentadecapeptide studied for its role in angiogenesis and tissue integrity. It is researched for its activity within growth-factor signaling pathways governing blood-vessel formation.', 'pepselect-child' ),
			'specs'       => array( 'CAS' => '137525-51-0', 'Formula' => 'C62H98N16O22', 'Form' => 'Lyophilized powder' ),
			'context'     => array(
				array( 'text' => __( 'Studied for promoting blood-vessel formation', 'pepselect-child' ), 'ref' => 1 ),
				array( 'text' => __( 'Researched for its role in tissue repair and integrity', 'pepselect-child' ), 'ref' => 2 ),
				array( 'text' => __( 'Investigated for its activity in growth-factor signaling', 'pepselect-child' ), 'ref' => 1 ),
			),
			'sources'     => array(
				'Wu W, et al. J Mol Med. 2017. DOI:10.1007/s00109-016-1488-y',
				'Cell Commun Signal. 2026. DOI:10.1186/s12964-026-02694-6',
			),
		),
	);

	return $library;
}

/**
 * Return the approved content for a product, or null.
 *
 * Matches on the product name reduced to a comparable key (lowercased,
 * strength suffixes and extra whitespace removed).
 *
 * @param WC_Product $product Product.
 * @return array<string,mixed>|null
 */
function pepselect_child_get_compound_content( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return null;
	}

	$key     = strtolower( trim( preg_replace( '/\s+/', ' ', $product->get_name() ) ) );
	$library = pepselect_child_compound_library();

	if ( isset( $library[ $key ] ) ) {
		return $library[ $key ];
	}

	foreach ( $library as $lib_key => $content ) {
		if ( 0 === strpos( $key, $lib_key ) ) {
			return $content;
		}
	}

	return null;
}
