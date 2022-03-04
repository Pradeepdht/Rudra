<?php
global $post;
get_header();
$post_id = $post->ID;
$unit_id = get_post_meta($post_id,'unit_id',true);
// print_r($unit_id);
?>
<!--Property info loader div start-->
<div class="loaderdiv skeleton-div">
    <div class="d-flex flex-wrap align-items-center loading-skeleton">
        <div class="loading-skeleton w-100">
            <img src="/wp-content/uploads/2022/02/loader-img.png" class="card-img-top" alt="...">
        </div>
    </div>
    <div class="d-flex flex-wrap my-5 mw-1140 px-2 loading-skeleton">
        <div class="loading-skeleton col-lg-60 px-3">
            <p>Lorem Ipsum is simply dummy </p>
            <div class="col-lg-80 mt-5 px-0">
            <p>Lorem Ipsum is simply dummy </p>
            </div>
            <div class="col-lg-70 mt-5 px-0">
            <p>Lorem Ipsum is simply dummy </p>
            </div>
            <div class="col-lg-100 px-0 mt-5"> 
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <div class="d-flex w-100 loader-info-div mt-5">
                    <div class="col-lg-50">
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                    </div>
                <div class="col-lg-50">
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                        <p>Lorem Ipsum is simply dummy </p>
                    </div></div>
            </div>
        </div>
        <div class="loading-skeleton col-lg-40 px-3">
            <img src="/wp-content/uploads/2022/02/loader-image2.png" class="card-img-top" alt="...">
            <img src="/wp-content/uploads/2022/02/loader-image2.png" class="card-img-top mt-4" alt="...">
        </div>
    </div>
</div>
<!--Loader div end-->
<!-- property banner starts here-->
<div class="single-property" data-postid="<?php echo $post_id;?>" data-unitid="<?php echo $unit_id;?>" style="display: none;">
</div>
<?php

get_footer();
?>