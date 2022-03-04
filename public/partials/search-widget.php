<form method='GET' name="roomssearchwidget" id="roomssearchwidget" action="<?php echo site_url(); ?>/search-results" class="schedule-form">
<div class=" d-flex align-items-center flex-wrap justify-content-between form-content px-2 mw-1140">
	<div class="custom-select pb-4">
		<label for="arrival" class="fw-bold text-white">Arrival Date</label>
   <input type="text" required="true" readonly="true" value="<?php if(isset($_GET['from-date'])){ echo esc_attr($_GET['from-date']); } ?>" id="fromdatepicker" name='from-date' class="datepicker" placeholder="Select From Date" />
</div>
<div class="custom-select pb-4">
	<label for="dep" class="fw-bold text-white">Departure Date</label>
  <input type="text" required="true" readonly="true" id="todatepicker" value="<?php if(isset($_GET['to-date'])){ echo esc_attr($_GET['to-date']); } ?>" name='to-date' class="datepicker" placeholder="Select To Date" />
</div>
<div class="custom-select pb-4">
	<label for="guest" class="fw-bold text-white ">Number of Guests</label>
  <select id="guests" name="guests">
    <?php for($i = 1; $i <= 4; $i++){
      $sel='';
      if(isset($_GET['guests']) && intval($_GET['guests']) == $i){
        $sel='selected="selected="';
      }
      ?>
      <option <?php echo $sel; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
      <?php

    } ?>  
  </select>
</div>
<div class="custom-select pb-4">
	<label for="room" class="fw-bold text-white">Room Type</label>
  <?php
    $room_types = [
      '0' => 'Any Room Type',
      // '5818' => 'Apartment',  //removed 
      // '5817' => 'Building',  // removed
      '7883' => 'Deluxe',
      '5819' => 'Parking',
      '7881' => 'Standard King',
       '7882' => 'Standard Queen',  
       '7884' => 'Suite'      
    ];
   ?>
  <select id="room-type" name="room-type">
    <?php foreach($room_types as $room_key => $room_type){
      $sel='';
      if(isset($_GET['room-type']) && intval($_GET['room-type']) == $room_key){
        $sel='selected="selected"';
      }
      ?>
      <option <?php echo $sel; ?> value="<?php echo $room_key; ?>"><?php echo $room_type; ?></option>
      <?php

    } ?>
  </select>
</div>
<div class="pb-4 align-self-end">
<button class="btn-light text-uppercase ">Search</button>
</div>
</div>
</form>