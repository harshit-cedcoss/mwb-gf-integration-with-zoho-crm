(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})(jQuery);

jQuery('document').ready(function () {


	// const preloader         = jQuery( '.mwb-zgf-desc--preloader' );
	const ajaxUrl = mwb_zgf_ajax.ajaxUrl;
	const auth = mwb_zgf_ajax.authSecure;
	const action = 'mwb_zgf_ajax_request';
	const notFound = mwb_zgf_ajax.notFoundError;
	const feedSavedSettings = mwb_zgf_ajax.feedFormSettings;
	const feedOptIn = mwb_zgf_ajax.feedFormFilters;
	const error = mwb_zgf_ajax.criticalError;
	const feedBackLink = mwb_zgf_ajax.feedBackLink;
	const feedBackText = mwb_zgf_ajax.feedBackText;
	const isPage = mwb_zgf_ajax.isPage;

	console.log(feedBackText);
	console.log(feedBackLink);

	var feed_settings;

	/**==================================================
					Liberary Functions.
	====================================================*/


	/**
	 * Initiliase the Table module.
	 *
	 * The function renders logs table on tab screen.
	 *
	 * @since      1.0.0
	 * @access     public
	 */
	const enableDataTable = () => {

		let ajax_url = jQuery('#mwb-zgf-logs').attr('ajax_url');
		ajax_url = ajax_url + '?action=get_datatable_logs';
		console.log(ajax_url);

		jQuery('#mwb-zgf-table').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": ajax_url,
			"scrollX": true,
			"dom": '<"bottom">tr<"bottom"ilp>', // extentions position
			"ordering": false, // enable ordering
			"pagination": false,
			responsive: {
				details: {
					type: 'column'
				}
			},

			columnDefs: [{
				className: 'dtr-control',
				orderable: false,
				targets: 0
			}],
			order: [1, 'asc'],

			language: {
				"lengthMenu": "Rows per page _MENU_",
				"info": "",
			}
		});
	}


	/**
	 * Add data to the current query.
	 *
	 * @param {string} uri   Query string.
	 * @param {string} key   Key to add to query.
	 * @param {string} value Value of the key to add to query.
	 * @since 1.0.0 
	 * @returns updated query.
	 */
	const updateQueryStringParameter = (uri, key, value) => {
		let re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		let separator = uri.indexOf('?') !== -1 ? "&" : "?";

		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		} else {
			return uri + separator + key + "=" + value;
		}
	}


	/**
	 * Ajax request callback.
	 * 
	 * @param {array} args An array of ajax arguments.
	 * @returns object
	 * @since 1.0.0
	 */
	async function doAjax(args) {

		try {
			return await jQuery.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: args,
				beforeSend: function () {

				},
			});

		} catch (error) {

			console.error(error);
		}
	}

	/**
	 * Get form fields on page load
	 *  
	 * @since 1.0.0
	 * @returns void
	 */
	const formFieldsOnPageLoad = () => {

		if (jQuery('#mwb_zgf_select_form').length == 0) {
			return;
		}

		let form_id = jQuery('#mwb_zgf_select_form').val();
		// alert( form_id );
		if ('-1' == form_id) {
			return;
		}

		let event = 'fetch_form_fields';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			form_id: form_id,
		};

		let result = doAjax(input);
		result.then((response) => {
			// alert('hi');
			// console.log( 'working' );
			console.log(response);
			feed_settings = response.data;
			// mappedField = jQuery( '.mwb-initial-filter' ).attr( 'mapped-field' );
			// let filter = createFilterFields( feed_settings, mappedField );
			// jQuery( '.condition-form-field' ).html( filter );
		});

	}

	/**
	 * Create tooltips via tiptip js.
	 * 
	 * @returns void.
	 * @since 1.0.0
	 */
	const display_tooltip = () => {

		let args = {
			'attribute': 'data-tip',
			'delay': 200,
			'defaultPosition': 'bottom',
		};

		jQuery('.mwb_zgf_tips').tipTip(args);
	}

	/**
	 * Define default feed title.
	 * 
	 * @since 1.0.0
	 * @returns void
	 */
	const defaultFeedTitle = () => {

		if (jQuery('body').hasClass('post-type-mwb_zoho_feeds')) {
			let feed_title = jQuery('input[name="post_title"]').val();
			let feed_id = jQuery('input[name="post_ID"]').val();
			if (!feed_title) {
				jQuery('input[name="post_title"]').val('Feed ' + '#' + feed_id);
			}
		}
	}

	/**
	 * Display back to feeds link on edit page.
	 * 
	 * @param {string} link Link to the page.
	 * @param {string} text Text to appear on link.
	 * @since 1.0.0 
	 * @returns void
	 */
	const backToFeeds = (link, text) => {
		if (jQuery('body').hasClass('post-type-mwb_zoho_feeds')) {
			// alert('working');
			jQuery('.page-title-action').after('<a class="page-title-action" href="' + link + '">' + text + '</a>');
		}
	}


	/**
	 * Hide visibility options.
	 * 
	 * @since 1.0.0
	 * @returns void
	 */
	const hideFeedEditOptions = () => {

		if (jQuery('body').hasClass('post-type-mwb_zoho_feeds')) {
			jQuery('div#visibility.misc-pub-section.misc-pub-visibility').css('display', 'none');
			jQuery('div#delete-action').css('display', 'none');
		}
	}

	/**
	 * Set fields if already have data.
	 * 
	 * @since 1.0.0
	 * @returns void
	 */
	const maybeSetFieldMapping = () => {

		if (jQuery('#mwb-feeds-zoho-object').length == 0) {
			return;
		}

		let module = jQuery('#mwb-feeds-zoho-object').val();

		if ('-1' == module) {
			return;
		}

		let event = 'fetch_module_fields';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			object: module,
		};

		let result = doAjax(input);
		result.then((response) => {
			console.log(response);
			if (true == response.status) {
				if (response.data.hasOwnProperty('crm_fields')) {
					crm_fields = response.data.crm_fields.fields;
					createFormFields(crm_fields, module);
					createAddNewFormField(crm_fields, true);
					createPrimaryFieldForm(true);
				}
			}
		});
	}

	/**
	 * Create Or filtered Html.
	 * 
	 * @param {integer} index Next or index.
	 * @returns html
	 * @since 1.0.0
	 */
	const createOrFilteredHtml = (index = 1) => {

		let html = '';

		html += '<div class="or-condition-filter" data-or-index="' + index + '">';
		html += '<div class="mwb-form-filter-row">';
		html += '<div class="and-condition-filter" data-or-index="' + index + '" data-and-index="1">';
		html += '<select name="condition[' + index + '][1][field]" class="condition-form-field">';
		html += '<option value="-1">Select Field</option>';

		html += createFilterFields(feed_settings);

		html += '</select>';
		html += '<select name="condition[' + index + '][1][option]" class="condition-option-field">';
		html += '<option value="-1">Select Condition</option>';

		jQuery.each(feedOptIn, function (_index, _value) {
			html += '<option value=' + _index + '>' + _value + '</option>';
		});

		html += '</select>';
		html += '<input type="text" name="condition[' + index + '][1][value]" class="condition-value-field" value="" placeholder="Enter value">';
		html += '</div>';

		if (1 != index) {
			html += '<span class="dashicons dashicons-trash"></span>';
		}

		html += '<button data-next-and-index="2" data-or-index="' + index + '" class="mwb-btn condition-and-btn">Add "AND" filter</button>';
		html += '</div></div>';

		return html;
	}


	/**
	 * Create And filtered Html
	 * 
	 * @param {integer} index   Next and Index.
	 * @param {integer} orIndex Next or Index
	 * @returns html
	 * @since 1.0.0
	 */
	const createAndFilteredHtml = (index = 1, orIndex = 1) => {

		let html = '';

		html += '<div class="and-condition-filter" data-and-index="' + index + '" data-or-index="' + orIndex + '">';
		html += '<select name="condition[' + orIndex + '][' + index + '][field]" class="condition-form-field">';
		// html += '<option value="-1">Select Field</option>';

		html += createFilterFields(feed_settings);

		html += '</select>';
		html += '<select name="condition[' + orIndex + '][' + index + '][option]" class="condition-option-field">';
		html += '<option value="-1">Select Condition</option>';

		jQuery.each(feedOptIn, function (_index, _value) {
			html += '<option value=' + _index + '>' + _value + '</option>';
		});

		html += '</select>';
		html += '<input type="text" name="condition[' + orIndex + '][' + index + '][value]" class="condition-value-field" value="" placeholder="Enter value">';
		if (1 != index) {

			html += '<span class="dashicons dashicons-no"></span>';
		}
		html += '</div>';

		return html;
	}

	/**
	 * Create filter form field options
	 * 
	 * @param {array} data Array of form fields
	 * @since 1.0.0
	 * @returns void
	 */
	const createFilterFields = (data = {}, mapData = '') => {

		if (jQuery.isEmptyObject(data)) {
			return;
		}
		console.log(mapData);
		console.log('check after this');
		console.log(data)

		let html = '<option value="-1">Select Field</option>';
		jQuery.each(data, function (index, value) {
			if (index == 'field_value') {
				jQuery.each(value, function (key, val) {
					if (key == 'options') {
						jQuery.each(val, function (i, v) {
							jQuery.each(v, function (field_k, field_v) {
								let s = (mapData == field_k) ? 'selected' : '';
								html += '<option value=' + field_k + ' ' + s + '>' + field_v + '</option>';
							});
						});
					}
				});
			}
		});
		return html;
	}


	/**
	 * Create selected form field.
	 * 
	 * @param {array} load An array of data of primary field.
	 * @returns void
	 * @since 1.0.0 
	 */
	const createPrimaryFieldForm = (load = false) => {

		let selected = jQuery('#primary-field-select').val();

		if (load) {
			selected = jQuery('#primary-field-select').attr('primary_field');
		}

		let options = '<option value="-1">Select Primary Key</option>';
		jQuery.each(jQuery('.crm-field-name'), function (index, value) {

			let val = jQuery(this).val();
			let lable = jQuery(this).closest('div').find('.field-label-txt').html();
			sel = val == selected ? 'selected' : '';
			options += '<option value="' + val + '" ' + sel + '>' + lable + '</option>';

		});

		jQuery('#primary-field-select').html(options);
		jQuery('#mwb-primary-field-section-wrapper').slideDown();
	}

	/**
	 * Create Form Fields.
	 * 
	 * @param {array}  fields An array of zoho fields. 
	 * @param {string} object Module selected.
	 * @returns void
	 * @since 1.0.0
	 */
	const createFormFields = (fields, object) => {

		jQuery('#mwb-fields-form-section-wrapper').slideUp();
		let html = '';
		let mapping = {};
		let crm_object = jQuery('#mwb-fields-form-section').attr('crm_object');

		if (crm_object == object) {
			mapping = jQuery('#mwb-fields-form-section').attr('mapping_data');
			mapping = JSON.parse(mapping);
		}

		jQuery.each(fields, function (index, value) {
			if (value.system_mandatory || mapping.hasOwnProperty(value.api_name)) {
				let mapping_data = mapping.hasOwnProperty(value.api_name) ? mapping[value.api_name] : {};

				html += getFormFieldRow(value, mapping_data);
				// console.log( html );
			}
		});

		jQuery('#mwb-fields-form-section').html(html);
		jQuery('#mwb-fields-form-section-wrapper').slideDown();

	}

	/**
	 * Create add new form field.
	 * 
	 * @param {array} fields   An array of zoho fields. 
	 * @param {array} map_data An array of map data of selected field.
	 * @returns void
	 * @since 1.0.0
	 */
	const createAddNewFormField = (fields, map_data = false) => {

		jQuery('#mwb-add-new-field-section-wrapper').slideUp();
		let mapping = {};

		if (map_data) {
			mapping = jQuery('#mwb-fields-form-section').attr('mapping_data');
			mapping = JSON.parse(mapping)
		}

		let html = '<option value="-1">Select Field</option>';

		jQuery.each(fields, function (index, value) {
			let disable = '';

			if (value.system_mandatory || mapping.hasOwnProperty(value.api_name)) {
				disable = 'disabled';
			}

			html += '<option value="' + value.api_name + '" ' + disable + ' >' + value.field_label + '</option>';
		});

		jQuery('#add-new-field-select').html(html);
		jQuery('#mwb-add-new-field-section-wrapper').slideDown();
	}

	/**
	 * Get module fields value.
	 * 
	 * @param {string} field        Zoho field
	 * @param {array}  mapping_data An array of mapping data.
	 * @returns html
	 * @since 1.0.0
	 */
	const getFormFieldRow = (field, mapping_data = {}) => {

		picklist_html = '';

		if (field.data_type == 'picklist') {
			jQuery.each(field.pick_list_values, function (index, value) {
				var limiter = '';
				// console.log(index );
				console.log(value);
				if (index < field.pick_list_values.length - 1) {
					limiter = ', ';
				}
				picklist_html += value.actual_value + ' = ' + value.display_value + limiter;
			});
		}

		let html = '<div class="mwb-feeds__form-wrap mwb-fields-form-row">';
		html += '<div class="mwb-form-wrapper">';
		html += '<div class="mwb-fields-form-section-head">';
		html += '<span class="field-label-txt">' + field.field_label + '</span>';
		html += '<input type="hidden" class="crm-field-name" name="crm_field[]" value="' + field.api_name + '"/>';

		if (!field.system_mandatory) {
			html += '<span class="field-delete dashicons dashicons-trash"></span>';
		}

		html += '</div>';
		html += '<div class="mwb-fields-form-section-meta">';
		html += '<span><strong>API Name : </strong>' + field.api_name + '</span>&#44;&nbsp;';
		html += '<span><strong>Type : </strong>' + field.data_type + '</span>&#44;&nbsp;';
		html += '<span><strong>Max Length : </strong>' + field.length + '</span>&#44;&nbsp;';

		if (picklist_html != '') {
			html += '<span><strong>Fields mapped with this field type must be form the following options</strong></strong><br/>'
			html += '<span><strong>Options : </strong>' + picklist_html + '</span>';
		}

		html += '</div>';
		html += '<div class="mwb-fields-form-section-form">';

		if (!jQuery.isEmptyObject(mapping_data)) {
			console.log('mapping_data');

			let hide_standard = (mapping_data.field_type == 'standard_field') ? '' : 'row-hide';
			let hide_custom = (mapping_data.field_type == 'custom_value') ? '' : 'row-hide';
			html += '<div class="mwb-fields-form-section-form">';
			html += '<div class="form-field-row row-field-type">';
			html += '<label>' + feedSavedSettings.field_type.label + '</label>';
			html += '<select class="field-type-select" name="field_type[]">';

			jQuery.each(feedSavedSettings.field_type.options, function (index, value) {
				let s = (mapping_data.field_type == index) ? 'selected' : '';
				html += '<option value="' + index + '" ' + s + '>' + value + '</option>';
			});

			html += '</select></div>';
			html += '<div class="form-field-row row-field-value row-standard_field ' + hide_standard + '">';
			html += '<label>' + feedSavedSettings.field_value.label + '</label>';
			html += '<select class="field-value-select" name="field_value[]" >';

			jQuery.each(feedSavedSettings.field_value.options, function (index, value) {
				html += '<optgroup label="' + index + '">';
				jQuery.each(value, function (i, v) {
					let key = index + '_' + i;
					let s = (mapping_data.field_value == key) ? 'selected' : '';
					html += '<option value="' + key + '" ' + s + ' >' + v + '</option>';
				});
				html += '</optgroup>';
			});

			html += '</select></div>';
			html += '<div class="form-field-row row-custom_value row-field-value ' + hide_custom + ' ">';
			html += '<div class="custom-value__wrap">';
			html += '<label>' + feedSavedSettings.custom_value.label + '</label>';
			html += '<div class="custom-value__fields">';
			html += '<input type="text" class="custom-value-input" name="custom_value[]" value="' + mapping_data.custom_value + '" />';
			html += '<div class="mwb_info">Choose form fields <code>{ field_id }</code> form the following form fields to add as custom values.</div>';
			html += '<select class="custom-value-select" name="custom_field[]" ></div></div>';

			jQuery.each(feedSavedSettings.field_value.options, function (index, value) {
				html += '<optgroup label="' + index + '">';
				jQuery.each(value, function (i, v) {
					let key = index + '_' + i;
					let s = (mapping_data.custom_field == key) ? 'selected' : '';
					html += '<option value="' + key + '" ' + s + ' >' + v + '</option>';
				});
				html += '</optgroup>';
			});

			html += '</select></div></div></div>';

		} else {
			console.log('no_data');

			html += '<div class="mwb-fields-form-section-form">';
			html += '<div class="form-field-row row-field-type">';

			html += '<label>' + feed_settings.field_type.label + '</label>';
			html += '<select class="field-type-select" name="field_type[]">';

			jQuery.each(feed_settings.field_type.options, function (index, value) {
				html += '<option value="' + index + '">' + value + '</option>';
			});

			html += '</select></div>';
			html += '<div class="form-field-row row-field-value row-standard_field">';
			html += '<label>' + feed_settings.field_value.label + '</label>';
			html += '<select class="field-value-select" name="field_value[]" >';

			jQuery.each(feed_settings.field_value.options, function (index, value) {
				html += '<optgroup label="' + index + '">';
				jQuery.each(value, function (i, v) {
					let key = index + '_' + i;
					html += '<option value="' + key + '" >' + v + '</option>';
				});
				html += '</optgroup>';
			});

			html += '</select></div>';
			html += '<div class="form-field-row row-custom_value row-hide row-field-value">';
			html += '<div class="custom-value__wrap">';
			html += '<label>' + feed_settings.custom_value.label + '</label>';
			html += '<div class="custom-value__fields">';
			html += '<input type="text" class="custom-value-input" name="custom_value[]" />';
			html += '<div class="mwb_info">Choose form fields <code>{ field_id }</code> form the following form fields to add as custom values.</div>';
			html += '<select class="custom-value-select" name="custom_field[]" >';
			html += '</div>';
			html += '</div>';

			jQuery.each(feed_settings.field_value.options, function (index, value) {
				html += '<optgroup label="' + index + '">';
				jQuery.each(value, function (i, v) {
					let key = index + '_' + i;
					html += '<option value="' + key + '" >' + v + '</option>';
				});
				html += '</optgroup>';
			});
			html += '</select></div>';
		}

		if (field.data_type == 'lookup') {
			html += '<div ><p class="mwb-desc" >Please map this field with an existing feed and make sure to trigger that feed before this feed.</p></div>';
		}

		html += '</div></div></div></div>';
		return html;
	}

	/**
	 * Create Object select.
	 * 
	 * @param {array} modules An array of zoho modules.
	 * @returns html.
	 * @since 1.0.0
	 */
	const createModuleSelect = (modules) => {

		let html = '<option>Select Object</option>';
		jQuery.each(modules, function (index, value) {
			if (value.hasOwnProperty('api_supported') && 1 == value.api_supported) {
				html += '<option value=' + value.api_name + '>' + value.module_name + '</option>';
			}
		});

		return html;
	}

	/**
	 * Disable selected option.
	 * 
	 * @param {string} value    Value of selected field.
	 * @param {object} selectId ID attribute of the field.
	 * @returns void
	 * @since 1.0.0 
	 */
	const disableSelectedOption = (value, selectId) => {

		let options = jQuery(selectId).find('option');

		jQuery.each(options, function () {
			if (value == jQuery(this).val()) {
				jQuery(this).prop('disabled', true);
			}
		});
	}

	/**
	 * Make tashed fields available.
	 * 
	 * @param {string} field_name Name of the field.
	 * @returns void
	 * @since 1.0.0
	 */
	const availTrashedFields = (field_name) => {
		let options = jQuery('#add-new-field-select').find('option');

		jQuery.each(options, function (index, value) {
			if (field_name == jQuery(this).attr('value')) {
				jQuery(this).removeAttr('disabled');
			}
		});
	}


	/**
	 * Display info for filters.
	 * 
	 * @since 1.0.0
	 * @returns void
	 */
	const displayInfoFilters = () => {

		let fields = jQuery('.condition-form-field, .condition-option-field, .condition-value-field, .condition-and-btn, .condition-or-btn');
		jQuery(fields).on('click', function (e) {
			e.preventDefault();

			let form = jQuery('#mwb_zgf_select_form').val();
			if ('-1' == form) {
				triggerInfo('Please select a form first');
				return;
			}

		});
	}

	/**
	 * Check if pgae is logs page and display datatable.
	 *
	 * @since 1.0.0
	 * @returns 
	 */
	const isLogsPage = () => {
		if ('logs' == isPage) {
			enableDataTable();
		}
	}

	/**
	 * Error alert via sweet alert-2.
	 * 
	 * @param {string} msg Message to alert.
	 * @returns void
	 * @since 1.0.0
	 */
	const triggerError = (msg = 'Something went wrong') => {
		Swal.fire({
			icon: 'error',
			title: 'Opps...!!',
			text: msg,
		});
	}

	/**
	 * Trash filter fields.
	 * 
	 * @param {object} Obj Object of row to delete.
	 * @returns void
	 * @since 1.0.0
	 */
	const trashFilterFields = (obj) => {
		obj.css({ 'opacity': '.5' });
		obj.fadeOut(500, function () {
			jQuery(this).remove();
		});
	}

	/**
	 * Info alert via sweet alert-2.
	 * 
	 * @param {string} msg Message to alert.
	 * @since 1.0.0
	 * @returns void
	 */
	const triggerInfo = (msg = '') => {
		Swal.fire({
			icon: 'info',
			title: 'Alert',
			text: msg,
		});
	}

	const filterFeedList = () => {
		let filter_form_id = jQuery('.mwb-zgf__from_search-wrap #mwb-zgf__from_search');
		let event = 'filter_feed_list';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			form_id: filter_form_id,
		}

		let result = doAjax(input);
		result.then((response) => {

		});
	}



	/**==================================================
				"on-load" function called here.
	====================================================*/

	/* Display tooltips */
	display_tooltip();

	/* Display back to feeds link */
	backToFeeds(feedBackLink, feedBackText);

	/* Default feed title */
	defaultFeedTitle();

	/* Set field mapping */
	maybeSetFieldMapping();

	/* Hide visibility and trash option on feed edit page */
	hideFeedEditOptions();

	/* Load form fields on page load */
	formFieldsOnPageLoad();

	/* Display filter info */
	displayInfoFilters();

	/* Display datatable */
	isLogsPage();

	/**==================================================
					Accounts Section JS.
	====================================================*/

	jQuery('.mwb-zgf__instruction-icon').on('click', function () {
		jQuery(this).toggleClass('icon-rotation');
		jQuery('.mwb-zgf__instruction').slideToggle();

	});

	jQuery('#info-expand').on('click', function () {
		jQuery(this).children('.mwb-zgf__expand-close-icon').toggleClass('icon-rotation');
		jQuery('.mwb-zgf__prod-info').slideToggle();
	});

	/* Toggle input type */
	jQuery('.mwb-toggle-view').on('click', function (e) {
		e.preventDefault();

		let input = jQuery(this).parent().siblings('input');

		if (jQuery(this).hasClass('dashicons-visibility')) {
			input.attr('type', 'text');
			jQuery(this).removeClass('dashicons-visibility').addClass('dashicons-hidden');
		} else {
			input.attr('type', 'password');
			jQuery(this).removeClass('dashicons-hidden').addClass('dashicons-visibility');
		}
	});


	/* AUthorize account */
	jQuery('#mwb-zgf-authorize-button').on('click', function (e) {
		e.preventDefault();
		href = jQuery(this).attr('href');
		client_id = jQuery('#mwb-zgf-client-id').val();
		secret_id = jQuery('#mwb-zgf-secret-id').val();
		domain = jQuery('#mwb-zgf-domain').val();

		if (client_id.length == 0 || secret_id.length == 0 || domain.length == 0) {
			return false;
		} else {
			href = updateQueryStringParameter(href, 'client_id', client_id);
			href = updateQueryStringParameter(href, 'secret_id', secret_id);
			href = updateQueryStringParameter(href, 'domain', domain);
			// console.log( jQuery( this ).attr( 'href', href ) )
			// Now Redirect.
			window.location.href = href;
		}
	});


	/* Refresh access token */
	jQuery('#mwb_zgf_refresh_token').on('click', function (e) {
		// e.preventDefault();

		jQuery(this).css('pointer-events', 'none');

		let event = 'refresh_access_token';
		let input = {
			action: action,
			nonce: auth,
			event: event,
		}
		let result = doAjax(input);
		result.then((response) => {
			// console.log( response );
			if (true == response.status) {
				if (true == response.data.success) {
					jQuery('#mwb-zgf-token-notice').text(response.data.token_message);
				}
			} else {
				triggerError(response.data.msg);
			}
		});
	});

	/* Reauthorize account */
	jQuery('#mwb_zgf_reauthorize').on('click', function (e) {
		e.preventDefault();

		let event = 'reauthorize_zgf_account';
		let input = {
			action: action,
			nonce: auth,
			event: event,
		}
		let result = doAjax(input);
		result.then((response) => {
			console.log(response);
			if (true == response.status) {
				window.location.href = response.data.url;
			} else {
				triggerError(response.data.msg);
				location.reload();
			}

		});
	});

	/* Revoke account access */
	jQuery('#mwb_zgf_revoke').on('click', function (e) {
		e.preventDefault();

		let event = 'revoke_zgf_access';
		let input = {
			action: action,
			nonce: auth,
			event: event,
		}
		let result = doAjax(input);
		result.then((response) => {

			if (true == response.status) {
				location.reload();
			} else {
				triggerError(response.message);
			}
		});
	});



	/**==================================================
					Feeds Section JS.
	====================================================*/

	/* Warning on empty feed title */
	jQuery('input[name="post_title"]').on('keyup', function () {
		let feed_title = jQuery('input[name="post_title"]').val();
		if (!feed_title) {
			let msg = '<span class="title_warning">*Title field cant\'t be empty</span>';
			jQuery('div#titlewrap').append(msg);
		}
	});


	/**Filter Feeds List based on the form */
	jQuery('.mwb-zgf__from_search-wrap').on('ready change load', '#mwb-zgf__from_search', function () {
		// alert('working');
		var form_id = jQuery(this).val();
		// alert(form_id);

		let event = 'filter_feed_list';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			form_id: form_id,
		}

		let result = doAjax(input);
		result.then((response) => {
			location.reload();
		});
	});


	/* On form select change */
	jQuery('#mwb_zgf_select_form').on('ready change', function () {

		let form_id = jQuery(this).val();

		if ('-1' == form_id) {
			triggerError('Please select a form');
			return;
		}

		let event = 'fetch_form_fields';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			form_id: form_id,
		};

		let result = doAjax(input);
		result.then((response) => {
			html = createOrFilteredHtml();
			jQuery('.condition-or-btn').before(html);
			jQuery('.condition-or-btn').attr('data-next-or-index', 2);
			console.log(response.data);
			feed_settings = response.data;
			let filter = createFilterFields(feed_settings);
			jQuery('.condition-form-field').html(filter);
		});
	});

	/* Get object fields on change */
	jQuery('#mwb-feeds-zoho-object').on('change', function () {

		if ('-1' == jQuery('#mwb_zgf_select_form').val()) {
			jQuery(this).prop('selectedIndex', 0);
			triggerInfo('Please select a form first');
			return;
		}

		let module = jQuery(this).val();
		if (module == '-1') return;

		let event = 'fetch_module_fields';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			object: module,
		};

		let result = doAjax(input);

		result.then((response) => {
			console.log(response);
			if (true == response.status) {
				if (response.data.hasOwnProperty('crm_fields')) {
					crm_fields = response.data.crm_fields.fields;
					createFormFields(crm_fields, module);
					createAddNewFormField(crm_fields);
				}
			}
		});
	});

	/* Refresh zoho objects */
	jQuery('.refresh-object').on('click', function () {

		let event = 'fetch_zoho_modules';
		let input = {
			action: action,
			nonce: auth,
			event: event,
		};

		jQuery(this).text('Loading...');
		jQuery(this).css('pointer-events', 'none');

		let result = doAjax(input);
		result.then((response) => {

			jQuery(this).text('Refresh Fields');
			jQuery(this).css('pointer-events', 'auto');

			if (true == response.status) {

				if (response.hasOwnProperty('data')) {
					if (response.data.hasOwnProperty('modules')) {
						let options_html = createModuleSelect(response.data.modules);
						jQuery('#mwb-feeds-zoho-object').html(options_html);
					}
				}
			} else {
				triggerError(response.message);
			}
		});
	});


	/* Refresh fields */
	jQuery('.refresh-fields').on('click', function () {

		let module = jQuery('#mwb-feeds-zoho-object').val();

		if ('-1' == module) {
			triggerError('Please select an Object first');
			return;
		}

		let event = 'fetch_module_fields';
		let force = true;
		let input = {
			action: action,
			nonce: auth,
			event: event,
			force: force,
			object: module,
		};

		let result = doAjax(input);
		result.then((response) => {
			// console.log( response );
			if (true == response.status) {
				if (response.data.hasOwnProperty('crm_fields')) {
					crm_fields = response.data.crm_fields.fields;
					createFormFields(crm_fields, module);
					createAddNewFormField(crm_fields);
				}
			}
		});
	});


	/* Add new map field */
	jQuery('#add-new-field-btn').on('click', function (e) {
		e.preventDefault();
		alert('working');
		let field = jQuery('#add-new-field-select').val();

		if ('-1' == field) {
			triggerInfo('Select a field first.');
		}

		console.log(crm_fields);

		let field_data;
		for (let index = 0; index < crm_fields.length; index++) {
			if (crm_fields[index].api_name == field) {
				field_data = crm_fields[index];
				break;
			}
		}

		if ('' != field_data) {
			let html = getFormFieldRow(field_data);
			console.log(html);
			jQuery('#mwb-fields-form-section').append(html);
			disableSelectedOption(field, '#add-new-field-select');
			jQuery('#add-new-field-select').val('-1');
			createPrimaryFieldForm();
		}
	});

	/* Trash fields */
	jQuery(document).on('click', '.mwb-fields-form-row .field-delete', function (e) {
		e.preventDefault();

		let form_row = jQuery(this).closest('.mwb-fields-form-row');
		let field_name = form_row.find('.crm-field-name').val();

		form_row.slideDown(400, function () {
			availTrashedFields(field_name);
			form_row.remove();
			createPrimaryFieldForm();
		});
	});

	/* Field type select */
	jQuery(document).on('change', '.field-type-select', function (e) {

		let type_select = jQuery(this);
		let field_type = type_select.val();
		let wrapper = type_select.closest('.mwb-fields-form-row');

		wrapper.find('.row-field-value').addClass('row-hide');
		wrapper.find('.row-' + field_type).removeClass('row-hide');
	});

	/* Custom field select */
	jQuery(document).on('change', '.custom-value-select', function (e) {
		let type_select = jQuery(this);
		let field_value = type_select.val();
		let wrapper = type_select.closest('.mwb-fields-form-row');
		let input = wrapper.find('.custom-value-input').val();

		wrapper.find('.custom-value-input').val(input + '{' + field_value + '}');
	});

	/* Save feed validation */
	jQuery('.post-type-mwb_zoho_feeds #publish').on('click', function (e) {

		let title = jQuery('#title').val();
		let crm_form = jquery('#mwb_zgf_select_form').val();
		let crm_object = jQuery('#mwb_zgf_object_select').val();

		if ('' == title) {
			triggerInfo('Title cannot be empty, please enter a feed title');
			e.preventDefault();
			return;
		}

		if ('-1' == crm_form) {
			triggerInfo('Please select a valid form');
			e.preventDefault();
			return;
		}

		if ('-1' == crm_object) {
			triggerInfo('Please select a  valid zoho object');
			e.preventDefault();
			return;
		}
	});


	/* Toogle feed status */
	jQuery('.mwb-feed-status').on('change', function () {

		let event = 'toggle_feed_status';
		let feedId = jQuery(this).attr('feed-id');

		if (this.checked) {
			let input = {
				action: action,
				nonce: auth,
				event: event,
				feed_id: feedId,
				status: 'publish',
			};

			let result = doAjax(input);
			result.then((response) => {
				if (true == response.status) {
					jQuery('.mwb-feed-status-text_' + feedId).text('Active');
				}
			});

		} else {

			let input = {
				action: action,
				nonce: auth,
				event: event,
				feed_id: feedId,
				status: 'draft',
			};

			let result = doAjax(input);
			result.then((response) => {
				if (true == response.status) {
					jQuery('.mwb-feed-status-text_' + feedId).text('Sandbox');
				}
			});
		}
	});

	/* Trash feed from feed list */
	jQuery('.mwb_zgf__trash_feed').on('click', function () {

		let row = jQuery(this).parent().closest('li');
		let feed_id = jQuery(this).attr('feed-id');
		let event = 'trash_feeds_from_list';
		let input = {
			action: action,
			nonce: auth,
			event: event,
			feed_id: feed_id,
		};

		let result = doAjax(input);
		result.then((response) => {

			if (true == response.status) {
				trashFilterFields(row);
			} else {
				triggerError(error);
			}

		});
	});


	/* Add or fields */
	jQuery(document).on('click', '.condition-or-btn', function (e) {
		e.preventDefault();

		let form = jQuery('#mwb_zgf_select_form').val();
		if ('-1' == form) {
			triggerInfo('Please select a form first');
			return;
		}

		let nextOrIndex = jQuery(this).attr('data-next-or-index');
		let nextOrHtml = createOrFilteredHtml(nextOrIndex);
		jQuery(this).before(nextOrHtml);
		nextOrIndex++;
		jQuery(this).attr('data-next-or-index', nextOrIndex);
	});


	/* Delete or filter */
	jQuery(document).on('click', '.dashicons-trash', function (e) {
		let row = jQuery(this).parents('.or-condition-filter');
		trashFilterFields(row);
	});


	/* Add and filter */
	jQuery(document).on('click', '.condition-and-btn', function (e) {
		e.preventDefault();

		let form = jQuery('#mwb_zgf_select_form').val();
		if ('-1' == form) {
			triggerInfo('Please select a form first');
			return;
		}

		let nextOrIndex = jQuery(this).attr('data-or-index');
		let nextAndIndex = jQuery(this).attr('data-next-and-index');
		let nextAndHtml = createAndFilteredHtml(nextAndIndex, nextOrIndex);

		jQuery(this).before(nextAndHtml);
		nextAndIndex++;
		jQuery(this).attr('data-next-and-index', nextAndIndex);
	});


	/* Delete and filter */
	jQuery(document).on('click', '.dashicons-no', function (e) {
		let row = jQuery(this).parents('.and-condition-filter');
		trashFilterFields(row);
	});


	/* Toogle feeds fields */
	jQuery('.inside .mwb-content-wrap a').on('click', function (e) {
		e.preventDefault();
		jQuery(this).toggleClass('active');
		jQuery(this).next('.mwb-feeds__meta-box-main-wrapper').toggle(100);
	});



	/**==================================================
						Logs Section JS.
	====================================================*/

	/* Clear Log */
	jQuery('#mwb-zgf-clear-log').on('click', function (e) {
		e.preventDefault();

		var event = 'clear_sync_log';
		var button = jQuery(this);
		button.text('Loading...');

		// console.log('clear logs working');
		let input = {
			action: action,
			nonce: auth,
			event: event,
		};

		let result = doAjax(input);
		result.then((response) => {
			if (true == response.status) {
				if (true == response.data.success) {
					button.text('Clear Log');
					window.location.reload();
				}
			} else {
				triggerError(error);
			}
		});
	});

	/* Download log */
	jQuery('#mwb-zgf-download-log').on('click', function (e) {
		e.preventDefault();

		// alert('download working');

		var event = 'download_sync_log';
		var button = jQuery(this);
		button.text('Loading...');

		let input = {
			action: action,
			nonce: auth,
			event: event,
		};

		let result = doAjax(input);
		result.then((response) => {
			// console.log( response );
			if (true == response.status) {
				if (true == response.data.success) {
					button.text('Download');
					location.href = response.data.redirect;
				}
			} else {
				triggerError(error);
			}
		});
	});


	/**==================================================	
	=				Settings Section JS.                =  
	====================================================*/

	/* Display email field on enable */
	jQuery('input[name="mwb_setting[enable_notif]"]').on('change', function () {

		let email_enable = jQuery(this).is(':checked');
		if (email_enable) {
			jQuery('#mwb_zgf_email_notif').removeClass('is_hidden');

		} else {

			jQuery('input[name="mwb_setting[email_notif]"]').val('');
			jQuery('#mwb_zgf_email_notif').addClass('is_hidden');
		}
	});


	/* Validations on imput type number */
	jQuery('input[name="mwb_setting[delete_logs]"]').on('keyup change focusout', function () {

		if (jQuery(this).val() < 0) {
			jQuery(this).val(0);
		}

		if (jQuery(this).val() % 1 != 0) {
			let value = jQuery(this).val();
			value = value.toString();
			let length = value.length;
			if (length > 4) {
				triggerError('Value must be Integer');
			}
		}
	});


	/**==================================================	
	=				Design Section JS.                =  
	====================================================*/


	var toggle_check = jQuery( '.mwb-switch__checkbox' );

	toggle_check.each( function(){
		if ( jQuery(this).is(':checked') ) {
			jQuery(this).parent( '.mwb-switch' ).addClass( 'mwb-switch__bg' );
			jQuery(this).addClass( 'mwb-switch__checkbox--move' );
		} else {
			jQuery(this).parent( '.mwb-switch' ).removeClass( 'mwb-switch__bg' );
			jQuery(this).removeClass( 'mwb-switch__checkbox--move' );
		}
	} );
	

	jQuery('.mwb-switch__checkbox').each( function() {

		jQuery(this).on('change', function () {
			jQuery(this).parent('.mwb-switch').toggleClass('mwb-switch__bg');
			jQuery(this).toggleClass('mwb-switch__checkbox--move');
		});
		
	});


});
