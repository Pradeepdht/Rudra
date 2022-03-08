(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
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
    // Retrieve your data from locaStorage

	//debugger;

    var saveData = JSON.parse(localStorage.saveData || null) || {};
    var propertyData = JSON.parse(localStorage.propertyData || null) || {};

    function getUrlVars()
    {
	var vars = [], hash;
	if ( window.location.href.indexOf('?') > -1 ){
	    var hashes = (window.location.href.slice(window.location.href.indexOf('?') + 1).split("#")[0]).split('&');
	    for (var i = 0; i < hashes.length; i++)
	    {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	    }
	}
	return vars;
    }

	// for debug 
	let  queryStringData = getUrlVars();
	let debug = queryStringData["debug"];
	if(queryStringData.indexOf("debug") !== -1)  
	{  
	  debugger;
	}

    function daysdifference(firstDate, secondDate) {
	var startDay = new Date(firstDate);
	var endDay = new Date(secondDate);

	// Determine the time difference between two dates     
	var millisBetween = startDay.getTime() - endDay.getTime();

	// Determine the number of days between two dates  
	var days = millisBetween / (1000 * 3600 * 24);

	// Show the final number of days between dates     
	return Math.round(Math.abs(days));
    }

    $(document).ready(function () {
	//Search widget
	if ($("#fromdatepicker").length > 0) {
	    $("#fromdatepicker").datepicker({
		dateFormat: "mm/dd/yy",
		minDate: 1,
		onSelect: function (selected) {
		    $("#todatepicker").datepicker("option", "minDate", selected);
		    setTimeout(function(){
			$("#todatepicker").datepicker('show');
		    }, 100);
		}
	    }).removeAttr("readonly");
	}
	if ($("#todatepicker").length > 0) {
	    $("#todatepicker").datepicker({
		dateFormat: "mm/dd/yy",
		minDate: 1,
		onSelect: function (selected) {
		    $("#fromdatepicker").datepicker("option", "maxDate", selected);
		}
	    }).removeAttr("readonly");
	}

	if ($('.searchresultdiv').length > 0) {
	    // Load LocalStorage
	    let localSt_Data = loadStuff();
	    // console.log(localSt_Data);
	    /*var startdate = jQuery("#roomssearchwidget").find("#fromdatepicker").val();
	     var enddate = jQuery("#roomssearchwidget").find("#todatepicker").val();
	     var occupants = jQuery("#roomssearchwidget").find("#guests").val();
	     var resort_area_id = jQuery("#roomssearchwidget").find("#room-type").val();
	     if(localSt_Data.startdate == startdate && localSt_Data.enddate == enddate && localSt_Data.occupants == occupants && localSt_Data.resort_area_id == resort_area_id){
	     console.log("Loaded from Local Storage");
	     // Load Search Page
	     setTimeout(function(){
	     $(".loaderdiv").hide();
	     $(".searchresultdiv").show().html(localSt_Data.template);
	     }, 1000);
	     
	     
	     } else {*/
	    console.log("Loaded from API");
	    // search data using API	 		
	    $.get(streampubobj.ajaxurl, 'action=sl_get_listing&' + jQuery("#roomssearchwidget").serialize(), function (result) {
		$(".loaderdiv").hide();
		//console.log(result);

		let response = JSON.parse(result);
		let api_json = JSON.parse(response.json);
		// console.log(api_json);

		// Save LocalStorage 
		var json_status = true;
		if (api_json.hasOwnProperty('status')) {
		    json_status = false;
		}
		let localData = {};
		localData['startdate'] = (json_status) ? api_json.data.startdate : "";
		localData['enddate'] = (json_status) ? api_json.data.enddate : "";
		localData['occupants'] = (json_status) ? api_json.data.occupants : "";
		localData['resort_area_id'] = (json_status) ? api_json.data.resort_area_id : "";
		localData['json'] = (json_status) ? response.json : "";
		localData['template'] = (json_status) ? response.template : "";
		saveStuff(localData);
		// Save LocalStorage End

		// Load Search Page
		$(".searchresultdiv").show().html(response.template);
	    });
	    // }
	}

	/**** Single propery page Start *****/
	if ($(".single-property").length > 0) {
	    var fromdate = '';
	    var todate = '';
	    var guests = '';
	    var price = '';
	    var discounts = '';
	    var fees = '';
	    var total = '';
	    var taxes = '';
	    var min_price = '';
	    const params = new URLSearchParams(window.location.search);
	    var unit_id = (params.has('unit_id')) ? params.get('unit_id') : 0;
	    if (unit_id == 0) {
		unit_id = $(".single-property").data('unitid');
	    }
	    var post_id = $(".single-property").data('postid');

	    // console.log(unit_id);
	    let localSt_Data = loadStuff();
	    let localPt_Data = loadProperty();
	    // console.log(localSt_Data);
	    var data = {
		'action': 'sl_get_property', //Action to store quotation in database
		'unit_id': unit_id,
		'post_id': post_id,
		'localData': localSt_Data,
		'localPt_Data': localPt_Data,
	    };

	    $.post(streampubobj.ajaxurl, data, function (result) {
		let response = JSON.parse(result);
		// console.log(response.property_data.blocked_period);

		var blocked_period = [];
		var blocked_period_data = response.property_data.blocked_period;
		if (blocked_period_data == null) {

		}else if (blocked_period_data.length > 1) {
		    $.each(blocked_period_data, function (index, val) {
			blocked_period.push([
			    val.startdate, val.enddate
			]);
		    });
		} else {
		    blocked_period.push([blocked_period_data.startdate, blocked_period_data.enddate]);
		}
		
		var queryparams = getUrlVars();
		if (queryparams.length > 1) {
		    fromdate = queryparams["fromdate"];
		    todate = queryparams["todate"];
		    guests = queryparams["guests"];
		    price = queryparams["price"];
		    discounts = queryparams["discounts"];
		    fees = queryparams["fees"];
		    taxes = queryparams["taxes"];
		    total = queryparams["total"];
		    min_price = queryparams["min_price"];
		    var total_nights = daysdifference(fromdate, todate);		
		    setTimeout(function () {
			if (fromdate != '' && typeof (fromdate) != "undefined") {
			    $('#checkindate').datepicker("setDate", fromdate);
			}
			if (todate != '' && typeof (todate) != 'undefined') {
			    $('#checkoutdate').datepicker("setDate", todate);
			}
			if (guests != '' && typeof (guests) != 'undefined') {
			    jQuery("#no_of_guest").val(guests);
			}
			if (parseFloat(price) > 0 && price != '' && typeof (price) != 'undefined') {
			    jQuery('.room-price').text('USD ' + price);
			} else {
			    jQuery('.room-price').parent().hide();
			}

			if (parseFloat(discounts) > 0 && discounts != '' && typeof (discounts) != 'undefined') {
			    jQuery('.room-discount').text('USD ' + discounts);
			} else {
			    jQuery('.room-discount').parent().hide();
			}
			if (parseFloat(fees) > 0 && fees != '' && typeof (fees) != 'undefined') {
			    //jQuery('.room-fees').text('USD 0');	
			    jQuery('.room-fees').text('USD ' + fees);
			} else {
			    jQuery('.room-fees').parent().hide();
			}

			if (parseFloat(taxes) > 0 && taxes != '' && typeof (taxes) != 'undefined') {
			    jQuery('.room-tax').text('USD ' + taxes);
			} else {
			    jQuery('.room-tax').parent().hide();
			}

			if (parseFloat(total) > 0 && total != '' && typeof (total) != 'undefined') {
			    jQuery('.room-total').text('USD ' + total);
			    jQuery('#single_property_payment_div .full_payment').text('USD ' + total);
				jQuery("#full_amount_of_room").val(total);
				
				checkRoomAvailabilityAjax(fromdate,todate,guests,unit_id,'');
			} else {
			    jQuery('.room-total').parent().hide();
			}

			if (parseFloat(min_price) > 0 && typeof (min_price) != 'undefined' && typeof (total_nights) != 'undefined') {
			    jQuery('.room-nights').text(min_price + ' x ' + total_nights + ' nights')
			} else {
			    jQuery('.room-nights').parent().hide();
			}

			jQuery('.str-book-now').prop("disabled", false);
		    }, 500);

		} else {
		    setTimeout(function () {
			jQuery('.price_section').css('display', 'none');
		    }, 500);
		}
   
		 

		// console.log(blocked_period);
		// Save LocalStorage 
		let localPropertyData = {};
		localPropertyData[unit_id] = response.property_data;
		localPropertyData['template'] = response.property_template;
		//console.log(localPropertyData);
		save_single_property(localPropertyData);
		// Save LocalStorage End

		//Save availabe room in cookie 
		let propertyData  = JSON.parse(localStorage.propertyData);
		let propData = JSON.parse(propertyData.pt);
		  blocked_period_data = propData[unit_id].blocked_period;

		let no_of_guest = parseInt(guests);
			let availabeRoom = [];
		availabeRoom.push({
			blocked_period:blocked_period_data,
			 checkin: fromdate,
			 checkout: todate,
			 no_of_guest:no_of_guest,
			 unit_id:unit_id,
			 price:price,
			 taxes:taxes,
			 coupon_discount:discounts,
			 total:total,
			// all_response:response
		});
		availabeRoom = JSON.stringify(availabeRoom);
		setCookie('m_checkout_data',availabeRoom,1);

		// Load Property Page
		setTimeout(function () {
		    $(".loaderdiv").hide();
		    $(".single-property").show().html(response.property_template);
		    // checkin/checkout		 				
		    if ($("#checkindate").length > 0) {

			$("#checkindate").datepicker({
			    dateFormat: "mm/dd/yy",
			    minDate: 1,
			    beforeShowDay: function (date) {

				var string = $.datepicker.formatDate('mm/dd/yy', date);

				for (var i = 0; i < blocked_period.length; i++) {

				    if (Array.isArray(blocked_period[i])) {

					var from = new Date(blocked_period[i][0]);
					var to = new Date(blocked_period[i][1]);
					var current = new Date(string);

					if (current >= from && current <= to)
					    return false;
				    }

				}
				return [blocked_period.indexOf(string) == -1]
			    },			   
			    onSelect: function (selected) {
				$("#checkoutdate").datepicker("option", "minDate", selected);
				setTimeout(function(){
				    $("#checkoutdate").datepicker('show');
				}, 100);
			    }
			})
		    }
		    if ($("#checkoutdate").length > 0) {

			$("#checkoutdate").datepicker({
			    dateFormat: "mm/dd/yy",
			    minDate: 1,
			    beforeShowDay: function (date) {

				var string = $.datepicker.formatDate('mm/dd/yy', date);

				for (var i = 0; i < blocked_period.length; i++) {

				    if (Array.isArray(blocked_period[i])) {

					var from = new Date(blocked_period[i][0]);
					var to = new Date(blocked_period[i][1]);
					var current = new Date(string);

					if (current >= from && current <= to)
					    return false;
				    }

				}
				return [blocked_period.indexOf(string) == -1]
			    },
			    onSelect: function (selected) {
				 $("#checkindate").datepicker("option", "maxDate", selected);				 
			    },
			    onClose: function () {
				checkavailabledates();
			    }
			})
		    }
		}, 100);
	    });
     

		
	$('body').on('click','.str-book-now',function(){	
			$(this).hide();
			$('#single_property_content_div').hide();
		
			$('.str-book-now_continue').show();
			$('#single_property_checkout_div').show();
			$('.house-rules').show();
			$('.str-book-now_continue').prop('disabled', true);
			single_room_page_contact_validate();
		});
		
		$('body').on('click','#single_property_checkout_div .back_btn',function(){
			$('.str-book-now_continue').hide();
			$('#single_property_checkout_div').hide();
			$('.house-rules').hide();
		
			$('.str-book-now').show();
			$('#single_property_content_div').show();
		
		});
		
		$('body').on('click','.str-book-now_continue',function(){
			$(this).hide();
			$('#single_property_checkout_div').hide();
		
			$('.str-book-now_payment').show();
			$('.str-book-now_payment').prop('disabled', true);
			$('#single_property_payment_div').show();
			credit_card_form_validate();

			if ($("#credit_card_details_form").valid()) {
				$('.str-book-now_payment').prop('disabled', false);  
			 } 

		});

		$('body').on('click','#single_property_payment_div .back_btn',function(){
			$('.str-book-now_payment').hide();
			$('#single_property_payment_div').hide();

			$('.str-book-now_continue').show();
			$('#single_property_checkout_div').show();
		});

		$('body').on('click','#single_property_payment_error .back_btn',function(){
			$('.str-book-now_continue').show();
			$('#single_property_payment_error').hide();
			$('#single_property_checkout_div').show();
		});

		$('body').on('click','#single_property_payment_success .back_btn',function(){
			$('.str-book-now_continue').show();
			$('#single_property_payment_success').hide();
			$('#single_property_checkout_div').show();
		});




		$('body').on('keyup','#single_property_checkout_div input,#single_property_checkout_div textarea',function(){
			// $('#single_property_checkout_div input,#single_property_checkout_div textarea').on('blur ', function() {
				// debugger;
				if ($("#single_property_checkout_div .contact_form").valid()) {
				   $('.str-book-now_continue').prop('disabled', false);  
				} else {
				   $('.str-book-now_continue').prop('disabled', true);
				}
			});

			//promo-code
			$('body').on('click','#promo-code',function(){
				$("#promo-code").attr("disabled", true);
				$("#promo-code").text('APPLING...');
				var checkin = $("#checkindate").val();
				var checkout = $("#checkoutdate").val();
				var no_of_guest =  $("#no_of_guest").val();
				var unit_id = $(".single-property").data('unitid');
				var coupon_code = $("#promo-code-input").val();
				checkRoomAvailabilityAjax(checkin,checkout,no_of_guest,unit_id,coupon_code);

			}); 


           // validate credit card form
		$('body').on('keyup','#credit_card_details_form input,#credit_card_details_form textarea, #single_property_checkout_div input,#single_property_checkout_div textarea',function(){

			var credit_card_form_has_error = false;
			$("#credit_card_details_form input,#credit_card_details_form select").each(function(){
				  if($(this).hasClass('error')){
					$('.str-book-now_payment').prop('disabled', true);
					credit_card_form_has_error =true; 
				  }
			  });

			  	if(credit_card_form_has_error ==false){
					if ($("#credit_card_details_form").valid()) {
						$('.str-book-now_payment').prop('disabled', false);  
					 } else {
						$('.str-book-now_payment').prop('disabled', true);
					 }
				  }
			});

			$('body').on('keyup','#single_property_checkout_div input,#single_property_checkout_div textarea',function(){
				if ($("#single_property_checkout_div .contact_form").valid()) {
					$('.str-book-now_payment').prop('disabled', false);  
				 } 
			});

			
		// Book Now Btn click
		$('body').on('click','.str-book-now_payment',function(){
			var checkin = $("#checkindate").val();
			var checkout = $("#checkoutdate").val();
			var no_of_guest =  $("#no_of_guest").val();
			var unit_id = $(".single-property").data('unitid');

			let first_name  = $("#single_property_checkout_div .contact_form .first_name").val();
			let last_name  = $("#single_property_checkout_div .contact_form .last_name").val();
			let email  = $("#single_property_checkout_div .contact_form .email").val();
			let zip_code  = $("#single_property_checkout_div .contact_form .zip_code").val();
			let city  = $("#single_property_checkout_div .contact_form .city").val();
			let address  = $("#single_property_checkout_div .contact_form .address").val();
			let notes  = $("#single_property_checkout_div .contact_form .notes").val();
			let phone_no  = $("#single_property_checkout_div .contact_form .phone_no").val();

			let cardholder_name  = $("#credit_card_details_form .cardholder_name").val();
			let credit_card_type  = $("#credit_card_details_form .credit_card_type").val();
			let credit_card_number  = $("#credit_card_details_form .credit_card_number").val();

			let exp_year = $("#credit_card_details_form #select_year").val()
			let exp_month = $("#credit_card_details_form #select_month").val()
			// let expiry_date  = $("#credit_card_details_form .expiry_date").val();
			let expiry_date  =exp_month+ '/'+exp_year;

			let cvv  = $("#credit_card_details_form .cvv").val();
			let credit_card_amount = $("#full_amount_of_room").val();
			// let credit_card_amount = $("#single_property_payment_div .full_payment").text();
			// credit_card_amount = credit_card_amount.replace(/\D/g,'');

			


			$('.str-book-now_payment').prop('disabled', true);
			$('.str-book-now_payment').text("Booking...");
			$(".price_section").after(' <div class="payment-loader availivilityloading"></div>');

			$.ajax({
				method: "POST",
				url: streampubobj.ajaxurl,
				data: { credit_card_type:credit_card_type,credit_card_amount:credit_card_amount,phone_no:phone_no,unit_id:unit_id,checkin:checkin,checkout:checkout,no_of_guest:no_of_guest,first_name: first_name,last_name:last_name,email:email,zip_code:zip_code,city:city,address:address,notes:notes,cardholder_name:cardholder_name,credit_card_number,expiry_date:expiry_date,cvv:cvv,action:"sl_book_room_now" },
				success:function(response) {
					//debugger;
					$('#single_property_payment_div').hide();
						var c_respone = JSON.parse(response);
						if(c_respone.hasOwnProperty('status')){
							//$("#single_property_payment_error .error_msg").empty();
							//$("#single_property_payment_error .error_msg").append(c_respone.status.description);
							$("#single_property_payment_error").show();
							$('.str-book-now_payment').prop('disabled', true);
					        savePaymentResponseInDb(first_name,last_name,'',email,response,'failed');

						}else{
							$("#payment_receipt .receipt_confirmation_id").text(c_respone.data.reservation.confirmation_id);
							$("#payment_receipt .receipt_location_name").text(c_respone.data.reservation.location_nam);
							$("#payment_receipt .receipt_condo_type_name").text(c_respone.data.reservation.condo_type_name);
							$("#payment_receipt .receipt_unit_name").text(c_respone.data.reservation.unit_name);
							$("#payment_receipt .receipt_startdate").text(c_respone.data.reservation.startdate);
							$("#payment_receipt .receipt_enddate").text(c_respone.data.reservation.enddate);
							$("#payment_receipt .receipt_occupants").text(c_respone.data.reservation.occupants);
							$("#payment_receipt .receipt_price_balance").text('USD '+c_respone.data.reservation.price_balance);
							$("#single_property_payment_success").show();
							savePaymentResponseInDb(first_name,last_name,c_respone.data.reservation.price_balance,email,response,'success');
						}

						$(".availivilityloading").remove();
						$('.str-book-now_payment').text("Book Now");
						$('.str-book-now_payment').prop('disabled', true);
						$('.str-book-now_payment').hide();

				  }
			});

		});

		// $( "#single_property_checkout_div .contact_form" ).validate();
		
	
	}
   /**** Single propery page End *****/

	// checking room Availability
	// $(window).on("load", function(){
	//     checkavailabledates();
	// });
	$(document).on("change", "#no_of_guest", function(){
	    checkavailabledates();
	});

	$(document).on('change','#cancel_inc',function() {
		
		jQuery("#cancel_inc").attr("disabled", true);
		jQuery(".price_section").css('display', 'none');
	 	jQuery(".price_section").after(' <div class="payment-loader availivilityloading"></div>');
    	var m_total = 0;
		var c_fees_c = jQuery(".badge-ins").attr('cancel-fee');
		var total_c = jQuery(".price_section .room-total").attr('old-price');
        if($(this).is(":checked") == false) {
            m_total = parseFloat(total_c);
        }else{
	        m_total = parseFloat(total_c)+parseFloat(c_fees_c);
        }
		m_total = m_total.toFixed(2);


        setTimeout(function(){
           	jQuery(".price_section .room-total").text('USD '+ m_total);
           	jQuery("#single_property_payment_div .full_payment").text('USD '+m_total);
		   	jQuery("#full_amount_of_room").val(m_total);
        	jQuery(".availivilityloading").remove();
        	jQuery(".price_section").css('display', 'block');
			jQuery("#cancel_inc").attr("disabled", false);
       }, 1000);
    });


	function checkavailabledates(){
	     var checkin = jQuery("#checkindate").val();
	     var checkout = jQuery("#checkoutdate").val();
	     var no_of_guest =  jQuery("#no_of_guest").val();
		 var unit_id = jQuery(".single-property").data('unitid');

		 //disable 
		 jQuery("#checkindate").attr("disabled", true);
		 jQuery("#checkoutdate").attr("disabled", true);
		 jQuery("#no_of_guest").attr("disabled", true);


	    
	     jQuery(".price_section").hide();
		 jQuery(".price_section").after(' <div class="payment-loader availivilityloading"></div>');
	     jQuery(".availivilityloading").show();
	     jQuery(".str-book-now").attr("disabled", true);
		 var coupon_code = $("#promo-code-input").val();
		 checkRoomAvailabilityAjax(checkin,checkout,no_of_guest,unit_id,coupon_code);
	     
	}

	// Store your data.
	function saveStuff(data) {
	    saveData.obj = data;
	    // saveData.foo = foo;
	    saveData.time = new Date().getTime();
	    localStorage.saveData = JSON.stringify(saveData);
	}
	// Store your data.
	function save_single_property(data) {
	    propertyData.pt = JSON.stringify(data);
	    // saveData.foo = foo;
	    propertyData.time = new Date().getTime();
	    localStorage.propertyData = JSON.stringify(propertyData);
	}
	// Store your data.
	function loadProperty(data) {
	    return propertyData.pt || "default";
	}

	// Do something with your data.
	function loadStuff() {
	    return saveData.obj || "default";
	}
	// console.log(loadStuff());

    });

    $(document).on('click', '.room_single_page_property_banner img', function () {
	if ($('.room_single_page_property_view_photos_click').length > 0) {
	    $('.room_single_page_property_view_photos_click')[0].click();
	}
    });

    function gotopropertydetail(unit_id) {
	window.location.href = streampubobj.site_url + "/property-info?unit_id=" + unit_id;
    }

})(jQuery);


function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	let expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
	  let c = ca[i];
	  while (c.charAt(0) == ' ') {
		c = c.substring(1);
	  }
	  if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
	  }
	}
	return "";
  }

  function deleteCookie(cname){
	document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  }

  function checkRoomAvailabilityAjax(checkin,checkout,no_of_guest,unit_id,coupon_code){
	jQuery(".m_room_not_found").remove();
	jQuery.ajax({
		method: "POST",
		url: streampubobj.ajaxurl,
		data: { checkin: checkin,checkout:checkout,no_of_guest:no_of_guest,unit_id:unit_id,coupon_code:coupon_code,action:"checkRoomAvailabilityAction" },
		success:function(response) {
			var c_respone = JSON.parse(response);
			if(c_respone.hasOwnProperty('status')){
				if(c_respone.status.code == 'E0031'){
					jQuery(".price_section").after(' <div class="m_room_not_found">We have no inventory available for the selected dates.</div>');
				}

			}else{
				// console.log(c_respone.data.required_fees);
				var total = c_respone.data.total;
				jQuery("#promo-code-msg").hide();
				if(c_respone.data.coupon_discount >0){
					jQuery("#promo-code-msg").show();
				 }

				var required_fees = c_respone.data.required_fees;
				if(required_fees.length > 0){
					var require_fee = 0;
					$.each(required_fees, function(index, val) {
					   require_fee += parseInt(val.value);
					});
				}
				if(c_respone.data.optional_fees.id == 211552){

					var c_fees = c_respone.data.optional_fees.value;
					c_fees = c_fees.toFixed(2);
					jQuery(".badge-ins").text('$'+c_fees);
					jQuery(".cancellation-fee").show();
					total = parseFloat(c_respone.data.total)+parseFloat(c_fees);
					jQuery(".badge-ins").attr('cancel-fee', parseFloat(c_fees));
					jQuery(".price_section .room-total").attr('old-price', parseFloat(c_respone.data.total));
					total = total.toFixed(2); 

				}
				//price_section 
				jQuery(".price_section .room-price").text('USD '+c_respone.data.price);
				jQuery(".price_section .room-fees").text('USD '+c_respone.data.taxes);
				
				jQuery(".price_section .room-total").text('USD '+total);

				jQuery("#single_property_payment_div .full_payment").text('USD '+total);
				jQuery("#full_amount_of_room").val(total);

				
				jQuery(".str-book-now").attr("disabled", false);
				jQuery(".price_section").show();
				
				//Save availabe room in cookie 
				let propertyData  = JSON.parse(localStorage.propertyData);
				let propData = JSON.parse(propertyData.pt);
				let blocked_period = propData[unit_id].blocked_period;

				no_of_guest = parseInt(no_of_guest);

				let availabeRoom = [];
				availabeRoom.push({
					blocked_period:blocked_period,
					 checkin: checkin,
					 checkout: checkout,
					 no_of_guest:no_of_guest,
					 unit_id:unit_id,
					 price:c_respone.data.price,
					 taxes:c_respone.data.taxes,
					 c_fees:c_fees,
					 total:total,
					// all_response:c_respone
				});
				availabeRoom = JSON.stringify(availabeRoom);
				setCookie('m_checkout_data',availabeRoom,1);

			}

			jQuery(".availivilityloading").remove();
			jQuery("#checkindate").attr("disabled", false);
			jQuery("#checkoutdate").attr("disabled", false);
			jQuery("#no_of_guest").attr("disabled", false);
			$("#promo-code").text('APPLY');
			$("#promo-code").attr("disabled", false);

		  }
	});

}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
    return [month, day, year].join('/');
}

//myArray.push({firstName: 1, lastName: 2, DOB:3});
 

function credit_card_form_validate(){
	jQuery( "#credit_card_details_form" ).validate();
		jQuery( "#credit_card_details_form .credit_card_number" ).rules( "add", {
			required: true,
			digits: true,
			minlength: 16,
			maxlength: 16,
			messages: {
			  required: "This field is required.",
			  minlength: jQuery.validator.format("Please enter  16 digit Credit card number"),
			  maxlength: jQuery.validator.format("Please enter  16 digit Credit card number")
			}
		  });

		  jQuery( "#credit_card_details_form .cvv " ).rules( "add", {
			required: true,
			digits: true,
			minlength: 3,
			maxlength: 3,
			messages: {
			  required: "This field is required.",
			  minlength: jQuery.validator.format("Please enter  3 digit cvv number"),
			  maxlength: jQuery.validator.format("Please enter  3 digit cvv number")
			}
		  });
}


function single_room_page_contact_validate(){
	jQuery( ".contact_form" ).validate();
	//debugger;
	jQuery( ".contact_form .zip_code " ).rules( "add", {
		required: true,
		digits: true,
		minlength: 5,
		maxlength: 5,
		messages: {
		  required: "This field is required.",
		  minlength: jQuery.validator.format("Please enter  5 digit Zip/Postal Code number"),
		  maxlength: jQuery.validator.format("Please enter  5 digit Zip/Postal Code number")
		}
	  });

}




function savePaymentResponseInDb(first_name_,last_name_,txn_amount_,email_,transaction_details_,status_s){
	//debugger;
	let first_name = first_name_;
	let last_name = last_name_;
	let txn_amount = txn_amount_;
	let email = email_;
	let transaction_details = transaction_details_;
	let status_ = status_s;

	jQuery.ajax({
		method: "POST",
		url: streampubobj.ajaxurl,
		data: { first_name: first_name,last_name:last_name,txn_amount:txn_amount,email:email,transaction_details:transaction_details,status:status_,action:"savePaymentResponseAction" },
		success:function(response) {
			//var c_respone = JSON.parse(response);
			console.log(response);

		  }
	});

}
