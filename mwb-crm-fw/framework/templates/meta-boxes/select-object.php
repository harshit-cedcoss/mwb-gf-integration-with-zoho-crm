<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select object section of feeds.
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
$modules = new Zoho_Gf_Helper();
$object  = $modules->get_modules();
?>
<div class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Select Object', 'zoho-cf7-integration' ); ?>
	</a>
	<?php
		$desc = esc_html__( 'Select the zoho object to create on form submission', 'zoho-cf7-integration' );

		Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc );
	?>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select name="crm_object" id="mwb-feeds-zoho-object" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Object', 'zoho-cf7-integration' ); ?></option>
					<?php if ( false != $object && is_array( $object ) ) : // @codingStandardsIgnoreLine?>
						<?php foreach ( $object as $key => $value ) : ?>
							<option value="<?php echo esc_html( $key ); ?>" <?php selected( $params['object'], $key ); ?>><?php echo esc_html( $value ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="mwb-form-wrapper">
				<a class="button refresh-object"><?php esc_html_e( 'Refresh Objects', 'mwb-woo-crm-fw' ); ?></a>
				<a class="button refresh-fields"><?php esc_html_e( 'Refresh Fields', 'mwb-woo-crm-fw' ); ?></a>
			</div>
		</div>
	</div>
</div>
