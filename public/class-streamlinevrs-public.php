<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.rudrainnovative.com
 * @since      1.0.0
 *
 * @package    Streamlinevrs
 * @subpackage Streamlinevrs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Streamlinevrs
 * @subpackage Streamlinevrs/public
 * @author     Rudra Innovative <parambir@rudrainnovatives.com>
 */
class Streamlinevrs_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Streamlinevrs_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Streamlinevrs_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	wp_enqueue_style($this->plugin_name . '-jquery-ui', plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', array(), $this->version, 'all');
	wp_enqueue_style($this->plugin_name . '-public', plugin_dir_url(__FILE__) . 'css/streamlinevrs-public.css', array(), $this->version . time(), 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Streamlinevrs_Loader as all of the hooks are defined
	 * in that particular class. 
	 *
	 * The Streamlinevrs_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */

	wp_enqueue_script( 'jquery-3.5.1', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), '1.0.0',false );
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script($this->plugin_name . 'public-js', plugin_dir_url(__FILE__) . 'js/streamlinevrs-public.js', array('jquery'), $this->version . time(), true);
	wp_localize_script($this->plugin_name . 'public-js', "streampubobj", array(
	    "ajaxurl" => admin_url('admin-ajax.php'),
	    "site_url" => site_url()
	));

	wp_enqueue_script( 'jquery-validate-js', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js', array(), '1.0.0',false );
	wp_enqueue_script( 'additional-methods-js', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js', array(), '1.0.0',false );
    
}

    public function sl_callback_shortcode_for_search() {
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/search-widget.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
    }

    public function sl_callback_shortcode__search_result_page() {
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/search-result-page.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
    }

	 public function checkRoomAvailabilityAction_handler()
	{
		$params = [];
		$params['owner_id'] = OWNER_ID;
		$fromdate = isset($_POST['checkin']) ? sanitize_text_field($_POST['checkin']) : date("m/d/Y");
		$todate = isset($_POST['checkout']) ? sanitize_text_field($_POST['checkout']) : date("m/d/Y");
		$guests = isset($_POST['no_of_guest']) ? intval($_POST['no_of_guest']) : 1;
		$unit_id = isset($_POST['unit_id']) ? sanitize_text_field($_POST['unit_id']) : '';

		$streamlinevrs_skip_units = get_option('streamlinevrs_skip_units');
		$streamlinevrs_skip_units = implode(',',$streamlinevrs_skip_units);

		$params['startdate'] = $fromdate;
		$params['enddate'] = $todate;
		$params['occupants'] = $guests;
		$params['unit_id'] = $unit_id;
		$params['include_coupon_information'] = 1;
		$params['seperate_taxes'] = 1;
		$params['return_new_pricing_model_and_rate_type'] = 1;
		$params['return_payments'] = "true";
		$params['optional_default_enabled'] = 1;
		$params['show_package_addons'] = 1;
		$params['pricing_model'] = 1;
		$params['skip_units'] = $streamlinevrs_skip_units;
		

		$propertiesResult = callStreamlineAPI('GetPreReservationPrice', $params);
		if ($propertiesResult && isset($propertiesResult->data)) {
		    $propertiesResultdata = $propertiesResult->data;
		    $p_price = $propertiesResultdata->price;
		    $p_total = $propertiesResultdata->total;
		    $p_taxes = $propertiesResultdata->taxes;
		    print_r($p_price);
		    print_r($p_total);
		    print_r($p_taxes);
		    $required_fees = $propertiesResultdata->required_fees;
		    foreach($required_fees as $required_fee){
		    	echo"<pre>";print_r($required_fee);echo "<pre>";
		    }
		}
	$p_response = $propertiesResult;
	wp_send_json($p_response);
	exit();

	}

	public function sl_book_room_now_handler(){
		
		$unit_id = isset($_POST['unit_id']) ? sanitize_text_field($_POST['unit_id']) : 0;
		$startdate = isset($_POST['checkin']) ? sanitize_text_field($_POST['checkin']) :'';
		$enddate = isset($_POST['checkout']) ? sanitize_text_field($_POST['checkout']) : '';
		$email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
		$occupants = isset($_POST['no_of_guest']) ? sanitize_text_field($_POST['no_of_guest']) : '';
		$first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
		$last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
		$city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
		$address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
		$notes = isset($_POST['notes']) ? sanitize_text_field($_POST['notes']) : '';
		$mobile_phone = isset($_POST['phone_no']) ? sanitize_text_field($_POST['phone_no']) : '';
		$cvv = isset($_POST['cvv']) ? sanitize_text_field($_POST['cvv']) : '';
		
		$credit_card_number = isset($_POST['credit_card_number']) ? sanitize_text_field($_POST['credit_card_number']) : '';
		$credit_card_amount = isset($_POST['credit_card_amount']) ? sanitize_text_field($_POST['credit_card_amount']) : '';
		$credit_card_type_id = isset($_POST['credit_card_type']) ? sanitize_text_field($_POST['credit_card_type']) : '';  
		$expiry_date = isset($_POST['expiry_date']) ? sanitize_text_field($_POST['expiry_date']) : '';
		$expiry_date = explode('/',$expiry_date);
		$credit_card_expiration_year =$expiry_date[1];
		$credit_card_expiration_month = $expiry_date[0];

		$zip = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
		$payment_type_id = 1;
		$bank_account_number = 1;
		$bank_routing_number = 1;
		$credit_card_charge_required = 1;

		$params = [];
		$params['owner_id'] = OWNER_ID;
		$params['unit_id'] = $unit_id;
		$params['startdate'] = $startdate;
		$params['enddate'] = $enddate;
		$params['email'] = $email;
		$params['occupants'] = $occupants;
		$params['first_name'] = $first_name;
		$params['last_name'] = $last_name;
		$params['city'] = $city;
		$params['address'] = $address;
		$params['mobile_phone'] = $mobile_phone;
		$params['credit_card_amount'] = $credit_card_amount;
		$params['credit_card_type_id'] = $credit_card_type_id;
		$params['credit_card_number'] = $credit_card_number;
		$params['credit_card_expiration_year'] = $credit_card_expiration_year;
		$params['credit_card_expiration_month'] = $credit_card_expiration_month;
		$params['credit_card_cid'] = $cvv;
		$params['zip'] = $zip;
		$params['payment_type_id'] = $payment_type_id;
		$params['bank_account_number'] = $bank_account_number;
		$params['bank_routing_number'] = $bank_routing_number;
		$params['credit_card_charge_required'] = $credit_card_charge_required;

		// print_r($params);
		// die();
		$propertiesResult = callStreamlineAPI('MakeReservation', $params); 
		$p_response = $propertiesResult;

		// print_r($_POST); 
	   wp_send_json($p_response);  
		exit();
	}


    public function sl_get_listing_handler() {
	$params = [];
	$params['owner_id'] = OWNER_ID;
	$fromdate = isset($_REQUEST['from-date']) ? sanitize_text_field($_REQUEST['from-date']) : date("m/d/Y");
	$todate = isset($_REQUEST['to-date']) ? sanitize_text_field($_REQUEST['to-date']) : date("m/d/Y");
	$guests = isset($_REQUEST['guests']) ? intval($_REQUEST['guests']) : 1;
	$roomtype = isset($_REQUEST['room-type']) ? sanitize_text_field($_REQUEST['room-type']) : '';
	$streamlinevrs_skip_units = get_option('streamlinevrs_skip_units');
	$streamlinevrs_skip_units = implode(',',$streamlinevrs_skip_units);

	$params['skip_units'] = $streamlinevrs_skip_units;
	$params['startdate'] = $fromdate;
	$params['enddate'] = $todate;
	$params['occupants'] = $guests;
	$params['home_type_id'] = $roomtype;
	$params['sort_by'] = 'random';

	$propertiesResult = callStreamlineAPI('GetPropertyAvailabilityWithRatesWordPress', $params);
	$p_response = $propertiesResult;

	$propertiesResult = json_decode($propertiesResult);
	$properties = [];
	if ($propertiesResult && isset($propertiesResult->data)) {
	    $propertiesResultdata = $propertiesResult->data;
	    if (isset($propertiesResultdata->available_properties)) {
		$properties_data = $propertiesResultdata->available_properties->property;
	    }

	    if (count($propertiesResultdata->available_properties->property) == 1) {
		// echo "Object Found";
		$properties[] = (object) $properties_data;
	    } else {
		$properties = $properties_data;
	    }
	}
	$unit_permalink = [];
	foreach ($properties as $per_prop) {
	    $str_id = $per_prop->id;


	    // Check Unit Post
	    $argu_array = array(
		'status' => 'publish',
		'post_type' => 'room',
		'posts_per_page' => -1,
		'return' => 'ids',
		'meta_query' => array(
		    array(
			'key' => 'unit_id',
			'value' => $str_id,
		    )
		)
	    );
	    $post_id = 0;
	    $query = new WP_Query($argu_array);
	    if ($query->have_posts()) {
		while ($query->have_posts()) {
		    $query->the_post();
		    $post_id = get_the_ID();
		    $unit_permalink[$str_id] = get_the_permalink();
		}
	    }
	}

	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/properties-page.php';
	$template = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('template' => $template, 'json' => $p_response));
	wp_die();
    }

    public function sl_callback_shortcode_prop_single_page() {
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-property-page.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
    }
     
	//checkout page
    public function streamlinevrs_checkout_sl_callback() {
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-checkout-page.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
    }
	//payment page 
    public function streamlinevrs_payment_sl_callback() {
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-payment-page.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
    }

    public function get_room_blocked_dates($unit_id) {
	$startdate = date("m/d/Y");
	$enddate = date("m/d/Y", strtotime($startdate . ' +1 year'));
	$parm['unit_id'] = $unit_id;
	$parm['startdate'] = $startdate;
	$parm['enddate'] = $enddate;
	$propertyAvailability = callStreamlineAPI('GetPropertyAvailabilityCalendarRawData', $parm);
	$propertyAvailability = json_decode($propertyAvailability, true);
	$blockedavailabilities = [];
	if ($propertyAvailability && isset($propertyAvailability['data'])) {
	    $propertyAvailabilitydata = $propertyAvailability['data'];
	    if (isset($propertyAvailabilitydata['blocked_period'])) {
		$blockedavailabilities = $propertyAvailabilitydata['blocked_period'];
	    }
	}
	return $blockedavailabilities;
    }

    public function sl_get_property_handler() {
	$params = [];
	$params['owner_id'] = OWNER_ID;
	$post_id = isset($_REQUEST['post_id']) ? intval($_REQUEST['post_id']) : 0;
	$unit_id = isset($_REQUEST['unit_id']) ? intval($_REQUEST['unit_id']) : 0;
	$localPt_Data = isset($_REQUEST['localPt_Data']) ? $_REQUEST['localPt_Data'] : '';
	//print_r($localPt_Data); die;
	$localData = isset($_REQUEST['localData']) && isset($_REQUEST['localData']['json']) ? stripslashes($_REQUEST['localData']['json']) : '';
	$localData = json_decode($localData);
	$all_properties = $localData->data->available_properties->property;

	$key = array_search($unit_id, array_column($all_properties, 'id'));
	$property = [];

	$propertyObjectDB = [];
	$propertyfromdb = get_post_meta($post_id, 'property_data', true);
	if ($propertyfromdb) {
	    $propertyObjectDB = json_decode($propertyfromdb);
	}
	if (!empty($localPt_Data[$unit_id])) {
	    // echo "From Local Property Storage<br>";
	    $localPt = $localPt_Data[$unit_id];
	    $property = $localPt['property'];
	    $galleryImages = $localPt['galleryImages'];
	    $amenities = $localPt['amenities'];
	    $blocked_period = $localPt['blocked_period'];
	    //$price_data_l = $localPt['price_data'];		
	    $property_info = array('property' => $property, 'galleryImages' => $galleryImages, 'amenities' => $amenities, 'blocked_period' => $blocked_period);
	} else {	   
	    $params['unit_id'] = $unit_id;

	    // GetPropertyInfo
	    if (isset($propertyObjectDB->property) && $propertyObjectDB->property != '') {
		$property = $propertyObjectDB->property;
	    } else {
		$propertyResult = callStreamlineAPI('GetPropertyInfo', $params);
		$propertyResult = json_decode($propertyResult, true);
		$property = [];
		if ($propertyResult && isset($propertyResult['data'])) {
		    $property = $propertyResult['data'];
		}
	    }

	    // GetPropertyGalleryImages
	    if (isset($propertyObjectDB->galleryImages) && $propertyObjectDB->galleryImages != '') {
		$galleryImages = $propertyObjectDB->galleryImages;
	    } else {
		$propertyImages = callStreamlineAPI('GetPropertyGalleryImages', $params);
		$propertyImages = json_decode($propertyImages, true);
		$galleryImages = [];
		if ($propertyImages && isset($propertyImages['data'])) {
		    $propertiesImagesdata = $propertyImages['data'];
		    if (isset($propertiesImagesdata['image'])) {
			$galleryImages = $propertiesImagesdata['image'];
		    }
		}
	    }

	    if (isset($propertyObjectDB->amenities) && $propertyObjectDB->amenities != '') {
		$amenities = $propertyObjectDB->amenities;
	    } else {
		$propertyAmenities = callStreamlineAPI('GetPropertyAmenities', $params);
		$propertyAmenities = json_decode($propertyAmenities, true);
		$amenities = [];
		if ($propertyAmenities && isset($propertyAmenities['data'])) {
		    $propertiesAmenitiesdata = $propertyAmenities['data'];
		    if (isset($propertiesAmenitiesdata['amenity'])) {
			$amenities = $propertiesAmenitiesdata['amenity'];
		    }
		}
	    }

	    $blockedavailabilities = $this->get_room_blocked_dates($unit_id);
	    if (empty($blockedavailabilities)) {
		$blockedavailabilities = $propertyObjectDB->blocked_period;
	    }

	    $property_info = array('property' => $property, 'galleryImages' => $galleryImages, 'amenities' => $amenities, 'blocked_period' => $blockedavailabilities);	    
	}
	// print_r($property_info);die;
	ob_start();
	include_once STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-property.php';
	$template = ob_get_contents();
	ob_end_clean();
	echo json_encode(array('property_data' => $property_info, 'property_template' => $template));
	// echo $template;
	wp_die();
    }

    public function streamline_custom_template($single) {

	global $post;

	// Checks for single template by post type /
	if ($post->post_type == 'room') {

	    if (file_exists(STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-room.php')) {
		return STREAMLINEVRS_PLUGIN_PUBLIC_DIR . 'partials/single-room.php';
	    }
	}

	return $single;
    }

    public function sl_get_property_total_price_data_handler() {
		print_r($_POST);
    }


    

	    public function get_all_streamline_properties() {
			global $user_ID;

			/* streamlinevrs All Owners id */
			$params = [];
			$params['owner_id'] = OWNER_ID;
			$all_unit_ids=[];
			$all_skip_units = [];
			$unitList = callStreamlineAPI('GetOwnerUnits', $params);	
			$unitListResult = json_decode($unitList);
			$properties = [];
			if ($unitListResult && isset($unitListResult->data)) {
				$unitListResultdata = $unitListResult->data;
				if (isset($unitListResultdata->owner->units)) {
					$units = $unitListResultdata->owner->units->unit;
					}
			}
			foreach ($units as $unit) {
				$all_unit_ids[] = $unit->id;
			}

			//main
			$params = [];
			
			$propertiesResult = callStreamlineAPI('GetPropertyList', $params);
			$p_response = $propertiesResult;

			$propertiesResult = json_decode($propertiesResult);

			$properties = [];
			if ($propertiesResult && isset($propertiesResult->data)) {
			$propertiesResultdata = $propertiesResult->data;
			if (isset($propertiesResultdata->property)) {
				$properties = $propertiesResultdata->property;
			}
			}

			foreach ($properties as $data) {
				$str_id = $data->id;
				if(in_array($str_id, $all_unit_ids)){

					$str_title = $data->name;
					$p_params = [];
					$p_params['unit_id'] = $str_id;

					// GetPropertyInfo
					$propertyResult = callStreamlineAPI('GetPropertyInfo', $p_params);
					$propertyResult = json_decode($propertyResult, true);
					$property = [];
					if ($propertyResult && isset($propertyResult['data'])) {
						$property = $propertyResult['data'];
						/* if( isset($propertiesResultdata->property) ){
						$property = $propertiesResultdata->property;
						} */
					}

					// GetPropertyGalleryImages
					$propertyImages = callStreamlineAPI('GetPropertyGalleryImages', $p_params);
					$propertyImages = json_decode($propertyImages, true);
					$galleryImages = [];
					if ($propertyImages && isset($propertyImages['data'])) {
						$propertiesImagesdata = $propertyImages['data'];
						if (isset($propertiesImagesdata['image'])) {
						$galleryImages = $propertiesImagesdata['image'];
						}
					}

					$propertyAmenities = callStreamlineAPI('GetPropertyAmenities', $p_params);
					$propertyAmenities = json_decode($propertyAmenities, true);
					$amenities = [];
					if ($propertyAmenities && isset($propertyAmenities['data'])) {
						$propertiesAmenitiesdata = $propertyAmenities['data'];
						if (isset($propertiesAmenitiesdata['amenity'])) {
						$amenities = $propertiesAmenitiesdata['amenity'];
						}
					}


					$blockedavailabilities = $this->get_room_blocked_dates($unit_id);
					$property_info = array('property' => $property, 'galleryImages' => $galleryImages, 'amenities' => $amenities, 'blocked_period' => $blockedavailabilities);
					// Custom Post Data
					$new_post = array(
						'post_title' => $str_title,
						'post_status' => 'publish',
						'post_author' => $user_ID,
						'post_type' => 'room',
					);

					// Check Unit Post
					$argu_array = array(
						'status' => 'publish',
						'post_type' => 'room',
						'posts_per_page' => -1,
						'return' => 'ids',
						'meta_query' => array(
						array(
							'key' => 'unit_id',
							'value' => $str_id,
						)
						)
					);
					$post_id = 0;
					$query = new WP_Query($argu_array);
					if ($query->have_posts()) {
						while ($query->have_posts()) {
						$query->the_post();
						$post_id = get_the_ID();
						}
					}

					// Check Unit Post End
					if ($post_id > 0) {
						// Update Post
						$new_post['ID'] = $post_id;
						$post_id = wp_update_post($new_post);
						if ($post_id > 0) {
						update_post_meta($post_id, 'unit_id', $str_id);
						update_post_meta($post_id, 'property_data', $property_info);
						}
					} else {
						// Create New Post
						$post_id = wp_insert_post($new_post);
						if ($post_id > 0) {
						update_post_meta($post_id, 'unit_id', $str_id);
						update_post_meta($post_id, 'property_data', $property_info);
						}
					}
				}else{
					$all_skip_units = $str_id;
					update_option('streamlinevrs_skip_units',$all_skip_units);
				}
				
			}

	    }
}
