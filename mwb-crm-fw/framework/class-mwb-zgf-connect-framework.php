<?php
/**
 * The complete management for the Zoho-gf Connect Framework.
 *
 * @since      1.0.0
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */

/**
 * The complete management for the Zoho-GF Connect Framework.
 *
 * @since      1.0.0
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Mwb_Zgf_Connect_Framework {

	/**
	 *  The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Main Mwb_Zgf_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Mwb_Woo_Crm_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Mwb_Zgf_Connect_Framework - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Main Mwb_Woo_Crm_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Mwb_Woo_Crm_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Mwb_Zgf_Connect_Framework - Main instance.
	 */
	public function __construct() {

	}

	/**
	 * Returns the mapping index from Woo we require.
	 *
	 * @param array $array The query object result.
	 * @since 1.0.0
	 * @return array The formatted data as raw array
	 */
	public function format_query_object( $array = false ) {

		if ( empty( $array ) ) {
			return;
		}

		$formatted_array = array();

		foreach ( $array as $key => $value ) {
			$dataset = array_values( $value );
			if ( ! empty( $dataset[0] ) ) {
				array_push( $formatted_array, $dataset[0] );
			}
		}

		return $formatted_array;
	}


	/**
	 * Returns the requested values for required form index.
	 *
	 * @param string $key      The form field meta key.
	 * @param array  $data     An array of form entries data.
	 * @since 1.0.0
	 * @return string The post meta values.
	 */
	public function get_prop_value( $key = false, $data = array() ) {

		if ( empty( $key ) || ! is_array( $data ) ) {
			return;
		}

		foreach ( $data as $field => $value ) {

			if ( $key == $field ) { // @codingStandardsIgnoreLine
				return $value;
			}
		}
	}

	/**
	 * Get other associated zoho object in required format.
	 *
	 * @param string $woo_obj_type   The WP post type.
	 * @param string $lookup_feed_id The Feed id to get lookup request.
	 * @param string $lookup_type    The CRM Object required id.
	 * @param string $woo_id         The WP post id.
	 * @since  1.0.0
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function resolve_lookup( $woo_obj_type, $lookup_feed_id, $lookup_type, $woo_id ) {

		if ( 'publish' == get_post_status( $lookup_feed_id ) ) { // @codingStandardsIgnoreLine

			switch ( $woo_obj_type ) {
				default:
					$status_obj = 'shop_order';
					break;
			}

			$zoho_association_id = get_post_meta( $woo_id, 'mwb_zoho_feed_' . $lookup_feed_id . '_association', true );

			if ( empty( $zoho_association_id ) ) {

				$request = $this->get_request( $status_obj, $lookup_feed_id, $woo_id );

				$record_type = $this->get_feed_meta( $lookup_feed_id, 'crm_object' );

				$log_data = array(
					'woo_id'     => $woo_id,
					'feed_id'    => $lookup_feed_id,
					'woo_object' => $status_obj,
				);

				$zoho_api = Zgf_Api::get_instance();
				$result   = $zoho_api->create_single_record( $record_type, $request, false, $log_data );

				$zoho_association_id = get_post_meta( $woo_id, 'mwb_zoho_feed_' . $lookup_feed_id . '_association', true );
			}
		}
		return ! empty( $zoho_association_id ) ? $zoho_association_id : '';
	}

	/**
	 * Returns the feed data we require.
	 *
	 * @param int    $feed_id  The object id for post type feed.
	 * @param string $meta_key The object meta key for post type feed.
	 * @return array|bool The current data for required object.
	 * @since 1.0.0
	 */
	public function get_feed_meta( $feed_id = false, $meta_key = 'mwb_zgf_mapping_data' ) {
		if ( false == $feed_id ) { // @codingStandardsIgnoreLine
			return;
		}

		$mapping = get_post_meta( $feed_id, $meta_key, true );

		if ( empty( $mapping ) ) {
			$mapping = false;
		}

		return $mapping;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param string $obj_type The object post type.
	 * @param string $feed_id  The mapped feed for associated objects.
	 * @param string $obj_id   The object post id.
	 * @param array  $entries  An array of form entries.
	 * @since  1.0.0
	 * @return array The current mapping step required.
	 */
	public function get_request( $obj_type = false, $feed_id = false, $obj_id = false, $entries = array() ) {

		if ( false == $obj_type || false == $feed_id || false == $obj_id || ! is_array( $entries ) ) { // @codingStandardsIgnoreLine
			return;
		}

		$feed = $this->get_feed_meta( $feed_id );

		if ( empty( $feed ) ) {
			return false;
		}

// 		echo '<pre>';echo 'feed_id'; print_r( $feed_id ); echo '</pre>';
// 		echo '<pre>';echo 'feed'; print_r( $feed ); echo '</pre>';
// echo '<pre>';echo 'entries'; print_r( $entries ); echo '</pre>';
// die('get request check');
		// Process Feeds.
		$response = array();

		foreach ( $feed as $k => $mapping ) {

			$field_type = ! empty( $mapping['field_type'] ) ? $mapping['field_type'] : 'standard_field';

			switch ( $field_type ) {

				case 'standard_field':
					$field_format = ! empty( $mapping['field_value'] ) ? $mapping['field_value'] : '';

					// If lookup field.
					if ( 0 === strpos( $field_format, 'feeds_' ) ) {
						$lookup_feed_id = str_replace( 'feeds_', '', $field_format );

						$field_value = $this->resolve_lookup( $obj_type, $lookup_feed_id, $k, $obj_id );
					} else { // Just a standard meta key.
						$obj_required = strtok( $field_format, '_' );
						$meta_key     = substr( $field_format, 20 );

						$field_value = $this->get_prop_value( $meta_key, $entries );
					}

					break;

				case 'custom_value':
					$field_key = ! empty( $mapping['custom_value'] ) ? $mapping['custom_value'] : '';

					preg_match_all( '/{(.*?)}/', $field_key, $dynamic_strings );

					if ( ! empty( $dynamic_strings[1] ) ) {
						$dynamic_values = $dynamic_strings[1];
						foreach ( $dynamic_values as $key => $value ) {
							$field_format = substr( $value, 20 );

							$field_value = $this->get_prop_value( $field_format, $entries );

							$substr = '{' . $value . '}';

							$field_key   = str_replace( $substr, $field_value, $field_key );
							$field_value = $field_key;
						}
					}

					break;
			}

			$response[ $k ] = ! empty( $field_value ) ? $field_value : '';
		}

		return $response;
	}

	/**
	 * Returns the mapping step we require.
	 *
	 * @param string $feed_type The CRM Object.
	 * @since 1.0.0
	 * @return bool|array single or multiple feed ids.
	 */
	public function get_feed_object( $feed_type = false ) {
		if ( empty( $feed_type ) ) {
			return false;
		}

		$args = array(
			'post_type'   => 'mwb_crm_feed',
			'post_status' => 'publish',
			'fields'      => 'ids',
			'meta_query'  => array( // @codingStandardsIgnoreLine
				'relation' => 'AND',
				array(
					'key'     => 'crm_object',
					'compare' => '=',
					'value'   => $feed_type,
				),
			),
		);

		$feeds = get_posts( $args );

		if ( ! empty( $feeds ) ) {
			if ( count( $feeds ) > 1 ) {
				$feed_id = $feeds;
			} else {
				$feed_id = reset( $feeds );
			}

			return $feed_id;
		}
		return false;
	}

	/**
	 * Get title of a particur feed.
	 *
	 * @param int $feed_id Id of feed.
	 * @since 1.0.0
	 * @return string
	 */
	public function get_feed_title( $feed_id ) {
		$title = 'Feed #' . $feed_id;
		$feed  = get_post( $feed_id );
		$title = ! empty( $feed->post_title ) ? $feed->post_title : $title;
		return $title;
	}

	// End of class.
}
