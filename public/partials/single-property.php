
<?php
if ( empty($property) ):
   ?>
   <div class="err-msg">
      <div class="no-property-found">
      <img src="/wp-content/uploads/2022/02/warning-sign-icon.png">
      <p class="mb-0 ms-2"><span class="fw-bold">Sorry! </span> The property you are looking for is removed from site.</p>
   </div>
</div>
   <?php
else:
   ?>
   <!-- <pre>
      <?php 
      // print_r($property);
      // print_r($galleryImages);
      // print_r($amenities);
      // die;
      ?>
   </pre> -->

   <?php 
   if ( !empty( $galleryImages ) ): 
   ?>
   <div class="property-banner mb-5 room_single_page_property_banner">
      <div class="col-lg-30">
         <img src="<?php echo (!is_object($galleryImages[0]['image_path'])) ? $galleryImages[0]['image_path'] : "/wp-content/uploads/2022/02/rental-default-img.png"; ?>" class="img-fluid rooms-bgcover-img" alt="">
         <img src="<?php echo (!is_object($galleryImages[1]['image_path'])) ? $galleryImages[1]['image_path'] : "/wp-content/uploads/2021/12/hotel-room-new-product-1330850.jpg"; ?>" class="img-fluid rooms-bgcover-img" alt="">
      </div>
      <div class="col-lg-40 p-0">
         <img src="<?php echo (!is_object($galleryImages[2]['image_path'])) ? $galleryImages[2]['image_path'] : "/wp-content/uploads/2022/01/hotel-massage.jpg"; ?>" class="img-fluid rooms-bgcover2-img" alt="">
      </div>
      <div class="col-lg-30">
         <img src="<?php echo (!is_object($galleryImages[4]['image_path'])) ? $galleryImages[4]['image_path'] : "/wp-content/uploads/2022/01/breakfast-free-img.jpg"; ?>" class="img-fluid rooms-bgcover-img" alt="">
         <div class="position-relative">
            <img src="<?php echo (!is_object($galleryImages[5]['image_path'])) ? $galleryImages[5]['image_path'] : "/wp-content/uploads/2022/01/toileteries-free-img.jpg"; ?>" class="img-fluid rooms-bgcover-img" alt="">
            <div class="position-absolute bottom-0 w-100">
               <div class="d-flex flex-wrap justify-content-center">
                  <a class="btn btn-sm btn-gold-banner mb-4 fw-normal mx-1" href="#virtual_popup">Virtual Tour</a>
                  <a class="btn btn-sm btn-gold-banner mb-4 fw-normal mx-1" href="#floor_plan_url_popup">Floor Plan</a>
                  <a class="btn btn-sm btn-gold-banner mb-4 fw-normal mx-1 room_single_page_property_view_photos_click" href="#popup3">View Photos</a>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php
   else:
   ?>
   <!-- Default property banner for 1 image starts here-->
      <div class="property-banner mb-5">
          <div class="col-lg-100 p-0">
              <div class="position-relative">
                  <img src="/wp-content/uploads/2022/02/Banner-default-img1.png" class="img-fluid rooms-bgcover3-img" alt="">
                  <div class="position-absolute bottom-0 w-100">
                      <div class="d-flex flex-wrap justify-content-center">
                          <a class="btn btn-sm btn-gold-banner mb-4 fw-normal mx-2" href="#virtual_popup">Virtual Tour</a>
                          <!-- <a class="btn btn-sm btn-gold-banner mb-4 fw-normal mx-2" href="#popup2">View Photos</a> -->
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- property banner ends here-->
   <?php 
   endif;  
   ?>
 
      <!-- Show slider on image click start -->
      <div id="popup3" class="overlay image-slides">
          <div class="popup">
              <a class="close" href="#close-me">&times;</a>
              <div class="content" id="close-me">
                  <div>
                      <div class="carousel">
                          <ul class="slides">

                          <?php 
                            $galleryImages_count = count($galleryImages);
                            $count_sr =  1; 
                            foreach($galleryImages as $galleryImage){
                                $isChecked = '';
                                if($count_sr ==1){
                                    $next_slide = 2;
                                    $prev_slide = $galleryImages_count;
                                    $isChecked = 'checked';
                                }
                                elseif($count_sr == $galleryImages_count){
                                    $next_slide = 1;
                                    $prev_slide = $galleryImages_count -1 ;

                                }else{
                                    $next_slide = $count_sr +1;
                                    $prev_slide = $count_sr -1 ;
                                }

                         
                          ?>
            
                              <input type="radio" name="radio-buttons" id="img-<?php echo $count_sr; ?>" <?php echo $isChecked ?> />
                                 <li class="slide-container">
                                  <div class="slide-image">
                                      <img src="<?php echo $galleryImage['image_path']; ?>">
                                  </div>
                                  <div class="carousel-controls">
                                      <label for="img-<?php echo $prev_slide; ?>" class="prev-slide">
                                          <span>&lsaquo;</span>
                                      </label>
                                      <label for="img-<?php echo $next_slide; ?>" class="next-slide">
                                          <span>&rsaquo;</span>
                                      </label>
                                  </div>
                              </li>
                         <?php  $count_sr++; }  ?>
  

                              <div class="carousel-dots">
                                  <?php 
                                    $count_sr =  1; 
                                    foreach($galleryImages as $galleryImage){
                                  ?>
                                      <label for="img-<?php echo $count_sr; ?>" class="carousel-dot" id="img-dot-<?php echo $count_sr; ?>"></label> 
                                  <?php $count_sr++; } ?>
                              </div>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div> 

   <!-- property banner ends here-->
   <!-- Property description starts here-->
   <div class="mb-5 property-info mw-1140 px-3">      
      <!-- <div id="single_property_content_div"> -->
            <div class="col-lg-60 pe-5" id="single_property_content_div">
               <h3 class="mb-0"><?php echo $property['name']; ?></h3>
               <h5 class="fw-normal mb-2" style="font-family: Montserrat, sans-serif;"><?php echo $property['location_area_name']; ?></h5>
               <div class="d-flex justify-content-between align-items-center py-2 flex-wrap info-label"><label><img class="pe-1" src="/wp-content/uploads/2022/01/clients.png" alt="guest" /> <?php echo sprintf("%02d", $property['max_occupants']); ?> Guests</label>
                  <label><img class="pe-1" src="/wp-content/uploads/2022/01/door.png" alt="studio" /> 01 Studio</label>
                  <label><img class="pe-1" src="/wp-content/uploads/2022/01/bedroom.png" alt="bed" /> <?php echo sprintf("%02d", $property['bedrooms_number']);?> Bed</label>
                  <label><img class="pe-1" src="/wp-content/uploads/2022/01/bath-room.png" alt="bathroom" /> <?php echo sprintf("%02d", $property['bathrooms_number']);?> Bathroom</label>
               </div>
               <h5 class="mt-5">Description</h5>
               <p><?php echo (!empty($property['short_description'])) ? strip_tags($property['short_description']) : "No description"; ?></p>
               <h5 class="mt-5 mb-2">Amenities</h5>
               <div class="facilities mb-5">
                  <div class="col-lg-100 ps-0">
                     <ul class="facility-list ms-0" style="list-style-type: none;">
                     <?php
                     // print_r($amenities);
                     if(!empty($amenities)){
                        for ($i=0; $i < count($amenities); $i++) { 
                           ?>
                           <li><?php echo $amenities[$i]['amenity_name'];?></li>
                           <?php
                        }
                     } else {
                        echo "<li>No amenities</li>";
                     }
                     ?>
                     
                     </ul>
                  </div>
               </div>
            </div>
      <!-- </div> -->

      <!-- <div id="single_property_checkout_div"  style="display: none;"> -->
      <div class="col-lg-60 mb-5" id="single_property_checkout_div" style="display: none;">
            <a href="javascript:void(0)" class="mb-0 fw-normal link-grey back_btn "><i aria-hidden="true" class="fas fa-long-arrow-alt-left pe-2"></i> BACK </a>
            <h5 class="mb-0 pe-5">Please fill your contact details.</h5>
            <form action="" class="contact_form">
                <div class="d-flex justify-content-between flex-wrap user-contact">
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold">First Name <sup>*</sup></label>
                            <input class="first_name" name="first_name" type="text" required="">
                        </div>
                    </div>
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold ">Last Name <sup>*</sup></label>
                            <input class="last_name" name="last_name"  type="text" required >
                        </div>
                    </div>
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold">Email <sup>*</sup></label>
                            <input class="email" name="email"  type="email" required="">
                        </div>
                    </div>
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold ">Phone Number<sup>*</sup></label>
                            <input  class="phone_no" name="phone_no"  type="number" required="">
                        </div>
                    </div>
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold ">Zip/Postal Code<sup>*</sup></label>
                            <input class="zip_code" name="zip_code"  type="number" required="" class="">
                        </div>
                    </div>
                    <div class="col-lg-50 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold ">City <sup>*</sup></label>
                            <input class="city" name="city"  type="text" required="">
                        </div>
                    </div>
                    <div class="col-lg-100 mt-3 ps-0">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold ">Address<sup>*</sup></label>
                            <input class="address" name="address" type="text" required="">
                        </div>
                    </div>
                    <div class="col-lg-100 ps-0 mt-3">
                        <div class="d-grid room-contact">
                            <label class="mb-2 fw-bold " >Notes <sup>*</sup></label>
                            <textarea class="notes" name="notes"  rows="3" required=""></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      <!-- </div> --> 

      <!-- <div id="single_property_payment_div" style="display: none;"> -->
           <div class="col-lg-60 " id="single_property_payment_div" style="display: none;">
            <a href="javascript:void(0)" class="mb-0 fw-normal link-grey back_btn"><i aria-hidden="true" class="fas fa-long-arrow-alt-left pe-2"></i> BACK </a>
            <h5 class="mb-0 pe-5">Please review the payment plan and enter your credit card details to secure your reservation.</h5>
            <div class="mt-5 payment-check-icon">
                <div class="d-flex align-items-center">
                    <img src="/wp-content/uploads/2022/02/payment-checkbox.png" alt="check-icon">
                    <h5 class="ps-3 mb-0 fw-normal">Today</h5>
                </div>
                <div class="ps-5 mt-2">Full Payment : <span class="fw-bold full_payment"></span> </div>
                <input type="hidden" val="" name="full_amount_of_room" id="full_amount_of_room">
            </div>
            <div class="payment-sec my-5">
                <div class="d-flex justify-content-center">
                     <form action="" id="credit_card_details_form">
                        <div class="mainDiv">
                            <div class="card-details">
                                <div class="mb-1">
                                    <label class="mb-1 ">Cardholder Name<sup>*</sup></label>
                                    <input class="cardholder_name" name="cardholder_name" required type="text" placeholder="Cardholder Name">
                                </div>
                                <div class="mb-1">
                                    <label class="mb-1 ">Credit Card Type<sup>*</sup></label>
                                    <select id="credit_card_type" name="credit_card_type"  class="credit_card_type">
                                        <option value="1">Visa</option>
                                        <option value="2">MasterCard</option>
                                        <option value="3">AmericanExpress</option>
                                        <option value="4">Discover</option>
                               </select>
                                </div>
                            </div>
                            <div class="mb-1">
                                <label class="mb-1 ">Credit Card Number<sup>*</sup></label>
                                <input type="number" name="credit_card_number" class="credit_card_number w-100" id="credit_card_number" placeholder="Credit Card Number" required>
                            </div>
                            <div class="card-details">
                                <div class="mb-1">
                                    <label class="mb-1 ">Exp. Date<sup>*</sup></label>
                                    <div class="d-flex justify-content-between">
                            <select id="select_month" required  name="select-month" class="credit_card_type" style="width: 130px;">
                                <?php
                                    for ($m=1; $m<=12; $m++) {
                                     $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                     echo "<option value='$m'>$month</option>";
                                     }
                                 ?> 
                            </select>
                            <select id="select_year" required name="select_year" class="credit_card_type ms-3" style="width: 90px;">
                                <?php
                                    $start =(int)date('Y');
                                	$end = (int)$start +15;
                                  for($n=$start; $n<=$end; $n++){
                                    echo " <option value='$n'>$n</option>";
                                  }

                                ?> 
                             </select>
                         </div>
                                </div>
                                <div class="mb-1 cvv-field">
                                    <label class="mb-1 ">cvv<sup>*</sup></label>
                                    <input class="cvv" name="cvv" placeholder="cvv" required type="number" style="width: 128px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class=" mt-3"><img src="/wp-content/uploads/2022/01/lock.png" class="pe-2 mt-1" alt="">Your credit card details will be processed with 128bit secured SSL connection</div>
            </div>
        </div>
     <!-- </div>  -->
     <div class="col-lg-60 " id="single_property_payment_error" style="display: none;">
        <a href="javascript:void(0)" class="fw-normal link-grey back_btn "><i aria-hidden="true" class="fas fa-long-arrow-alt-left pe-2"></i> BACK </a>
        <div class="errorDiv text-center shadow my-3">
           <img src="/wp-content/uploads/2022/03/error-bg-img.png" alt="">
           <h5 class=" mb-0">We Needs your Attention Please!</h5>
           <div class="error_msg p-4 pb-5">There are some errors during the form processing, Please Try Again...</div>
        </div>
     </div>
     <div class="col-lg-60" id="single_property_payment_success" style="display:none;">
            <div class="success-table mb-5">
                <div class="text-center position-relative">
                    <a href="javascript:void(0)" class="fw-normal link-grey back_btn "><i aria-hidden="true" class="fas fa-long-arrow-alt-left pe-2"></i> BACK </a>
                <h4>Payment Successfull</h4>
            </div>
                <div class="text-center mb-4">
                    <img src="/wp-content/uploads/2022/03/success-img.png" class="text-center" alt="">
                </div>
                <table id="payment_receipt" class="mb-0">
                    <tr>
                        <td>Confirmation id</td>
                        <td class="receipt_confirmation_id"></td>
                    </tr>
                    <tr>
                        <td>Location Name</td>
                        <td class="receipt_location_name"></td>
                    </tr>
                    <tr>
                        <td>Condo Type Name</td>
                        <td class="receipt_condo_type_name"></td>
                    </tr>
                    <tr>
                        <td>Room Name</td>
                        <td class="receipt_unit_name"></td>
                    </tr>
                    <tr>
                        <td>Check In</td>
                        <td class="receipt_startdate"></td>
                    </tr>
                    <tr>
                        <td>Check Out</td>
                        <td class="receipt_enddate"></td>
                    </tr>
                    <tr>
                        <td>Guests</td>
                        <td class="receipt_occupants"></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td class="receipt_price_balance"></td>
                    </tr>
                </table>
            </div>
        </div>
      
      <!-- Property description ends here-->
      <!-- Form starts here-->
      <div class="col-lg-40">
         <div class="p-4 avail-widget" style="background-color: #F1F1F1;">
            <div class=" d-flex align-items-center flex-wrap justify-content-between p-0">
               <div class="custom-select2 pb-4">
                  <label for="guest" class="fw-bold">Check In</label>
                 <input type="text" required="true" readonly="true" value="" id="checkindate" name='from-date' class="checkincheckout" placeholder="Select From Date" />
               </div>
               <i aria-hidden="true" class="fas fa-long-arrow-alt-right mt-2"></i>
               <div class="custom-select2 pb-4">
                  <label for="room" class="fw-bold">Check Out</label>
                  <input type="text" required="true" readonly="true" value="" id="checkoutdate" name='from-date' class="checkincheckout" placeholder="Select To Date" />
               </div>
            </div>
            <div class="custom-select2 pb-4">
               <label for="room" class="fw-bold"># of Guests</label>
               <select id="no_of_guest">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
               </select>
            </div>
            <div class="custom-select2 pb-4">
                    <label for="room" class="fw-bold">Add a Promo Code</label>
                    <div class="d-flex promo-field position-relative">
                        <input type="text">
                        <button class="btn btn-gold text-uppercase" id="promo-code">Apply</button>
                    </div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <label class="custom-check pt-1 my-3">Cancellation Insurance
                        <input type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                    <div class="badge-ins my-3">$56.35</div>
                </div>
            <div class="price_section">
            <div class="d-flex justify-content-between py-3 border-bottom">
               <div class="d-grid">
                  <label class="fw-bold">Subtotal </label>                  
               </div>
               <label class="room-price">-</label>
            </div>
            <div class="d-flex justify-content-between py-3 border-bottom">
               <label class="fw-bold">Discount</label>
               <label class="room-discount">-</label>
            </div>
            <div class="d-flex justify-content-between py-3 border-bottom">
               <label class="fw-bold">Taxes And Fees</label>
               <label class="room-fees">-</label>
            </div>
            <div class="d-flex justify-content-between py-3 ">
               <label class="fw-bold">Total</label>
               <label class="room-total">-</label>
            </div>           
            </div>
             <div class="d-grid">
                <button class="str-book-now btn btn-gold mb-4 px-4 text-uppercase mt-5" disabled>Book Now</button>
                <button class="str-book-now_continue btn btn-gold mb-4 px-4 text-uppercase mt-5" style="display: none;">Continue to Payment</button>
                <button class="str-book-now_payment  btn btn-gold mb-4 px-4 text-uppercase mt-5" style="display: none;">Book Now</button>
            </div>
         </div>
             <div class="p-4 house-rules mt-5" style="display:none;">
                <h5 class="border-bottom pb-3 mb-3">House Rules</h5>
                <p>1. Guests are required to complete the reservation process using a platform called Duve. You will receive an email after booking to collect identification and confirm travel specifics. Completing the process provides you with information necessary to access the property as well as helpful local resources.</p>
                <p>2. We are a quiet building and we strive to make guests feel in the comfort of their own home. We ask guests to keep TV volume at low and observe quiet hours between 11:00pm and 7:00am.</p>
                <div class="d-grid mt-3"><button class="btn btn-gold mb-4 px-4 text-uppercase">Read More</button></div>
            </div>
      </div>
      <!-- Form ends here-->
   </div>

   <!--custom modal starts here-->
    <div id="virtual_popup" class="overlay">
       <div class="popup">
           <h4>Virtual Tour</h4>
           <a class="close" href="#close-me">&times;</a>
           <div class="content text-center" id="close-me">
               <?php echo (!empty($property['virtual_tour_url'])) ?  $property['virtual_tour_url'] : "<p>No Virtual View.</p>";?>
           </div>
       </div>
   </div>
   <!--custom modal ends here-->

   <!--custom modal starts here-->
    <div id="floor_plan_url_popup" class="overlay">
       <div class="popup">
           <h4>Floor Plan </h4> 
           <a class="close" href="#close-me">&times;</a>
           <div class="content text-center" id="close-me">
               <?php echo (!empty($property['floor_plan_url'])) ?  $property['floor_plan_url'] : "<p>Sorry! Floor Plan is not available for his room.</p>";?>
           </div>
       </div>
   </div>
   <!--custom modal ends here--> 
<?php 
endif;     
?>