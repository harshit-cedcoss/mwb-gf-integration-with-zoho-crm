<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the primary field of feeds section.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes/framework/templates/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<pre>';echo '2'; print_r( $params ); echo '</pre>';
global $post;
$form_id = get_post_meta( $post->ID, 'mwb_zgf_form', true );
?>
<div id="mwb-condition-filter-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Condition Filter', 'zoho-gf-integration' ); ?>
	</a>
	<?php
		$desc = esc_html__( 'If the condition filters are set, only those submissions will be exported which fullfills the condition.', 'zoho-gf-integration' );

		Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc );
		$prefilled_indexes = count( $params );
	?>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper  mwb-form-filter-wrapper">
				<div class="mwb-initial-filter">
					<?php if ( ! empty( $params ) && is_array( $params ) ) : ?>
						<?php foreach ( $params as $or_index => $and_conditions ) : ?>
							<div class="or-condition-filter" data-or-index="<?php echo esc_html( $or_index ); ?>">
								<div class="mwb-form-filter-row">
									<?php foreach ( $and_conditions as $and_index => $and_condition ) : ?>
										<?php
										$form_data  = GFAPI::get_form( $form_id );
										$form_field = Zoho_Gf_Helper::get_form_fields( $form_data );

										$and_condition['form'] = $form_field;
										?>
										<?php Zoho_Gf_Integration_Admin::render_and_conditon( $and_condition, $and_index, $or_index ); ?>
									<?php endforeach; ?>
									<button data-next-and-index="<?php echo esc_html( ++$and_index ); ?>" class="button condition-and-btn"><?php esc_html_e( 'Add "AND" filter', 'zoho-gf-integration' ); ?></button>
									<?php if ( 1 != $prefilled_indexes ) :  // @codingStandardsIgnoreLine?>
										<span class="dashicons dashicons-trash"></span>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<button data-next-or-index="<?php echo esc_html( ++$prefilled_indexes ); ?>" class="button condition-or-btn"><?php esc_html_e( 'Add "OR" filter', 'zoho-gf-integration' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>


