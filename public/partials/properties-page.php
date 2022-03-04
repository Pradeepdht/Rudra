<?php
$query_string = '';
if ( count( $properties ) == 0 ):
	?>
	<!-- No data found -->
<div class="d-flex flex-wrap align-items-center justify-content-center mw-1140 no-results-found">
    <img src="/wp-content/uploads/2022/02/no_results-img.png" class="px-3 no-results-img" alt="no-results-found">
    <div class="px-3">
        <h3 class="fw-bold mb-2 mt-4">Whoops!</h3>
        <h6 style="font-family: 'Montserrat',sans-serif;" class="fw-normal"><span class="fw-bold">Sorry,</span> We do not find any room. Refine your search</h6>
    </div>
</div>
<!-- No data found -->
	<?php
else:
foreach( $properties as $prop ): 
$query_string = 'fromdate='.$fromdate.'&todate='.$todate.'&discounts='.$prop->coupon_discount.'&min_price='.$prop->minimum_day_price.'&guests='.$guests.'&price='.$prop->price.'&fees='.$prop->fees.'&taxes='.$prop->taxes.'&total='.$prop->total;
	?>
<div class="card shadow roomdivclick" data-attr='<?php echo json_encode($prop); ?>' data-permalink="<?php echo $unit_permalink[$prop->id]; ?>">
	<div class="card-body p-0 position-relative">
		<div class="card-img">
			<img src="<?php echo (!is_object($prop->default_thumbnail_path)) ? $prop->default_thumbnail_path : "/wp-content/uploads/2022/02/rental-default-img.png

"; ?>" class="img-fluid rooms-cover-img" alt="">
		</div>
		<div class="img-overlay">
			<div class="d-flex justify-content-between align-items-center px-4 py-2">
				<label><img src="/wp-content/uploads/2022/01/guest-icon01.png" class="pe-1" alt=""> <?php echo $prop->max_occupants; ?></label>
				<label>$<?php echo $prop->minimum_day_price; ?> per night</label>
			</div>
		</div>
	</div>
	<div class="card-footer p-4">
		<h5 class="text-muted mb-0 fw-normal"><?php echo $prop->name; ?></h5>
		<div class="d-flex align-items-center mt-3">		
		<label class="text-muted fw-normal badge-light"><?php echo (!is_object($prop->home_type)) ? $prop->home_type : ""; ?> </label>		
	</div>
	</div>
</div>
<?php 
endforeach; 
endif;
?>
<script type="text/javascript">
	var querystring = '<?php echo $query_string ?>';
	jQuery(".roomdivclick").click(function(){
		let permalink = jQuery(this).data('permalink');
		window.location.href = permalink+'?'+querystring;
	});
</script>

<?php 

if(isset($_GET['new_mani'])){
	echo "<pre>";
	print_r($prop);
}

?>