 <?php


 function LMEasySlider_modifica_slider() {
 	global $wpdb;
 	global $post;
 	$lmEasySliderId = 'lm-easy-slider-' . $post->ID;

 	$res_select = $wpdb->get_row( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '$lmEasySliderId'" );
 	if ( $res_select != '' ) {
 		$slider_name = $res_select->meta_key;
 		$slider_value = $res_select->meta_value;
 		$slider = json_decode( $slider_value );
 		$idimg = lmeasy_control_media_esistenti($slider->idimg, $post->ID );
 	} else {
 		$slider_name = $lmEasySliderId;
 		$slider_value = "";
 		$idimg = "";
 	}
 	wp_enqueue_media();
 	?>
	<style>
		.image-preview-wrapper,
		.button-preview-wrapper {
			border: none;
			text-align: center;
			overflow: hidden;
			float: left;
		}
		
		.imageFrame {
			float: left;
			padding: 5px;
			margin: 5px;
		}
		
		.checkImage {
			position: relative;
			z-index: 100;
			top: 6px;
			text-align: right;
		}
		
		.immagine {
			margin-top: -15px;
		}
		
		.button-plus {
			background-color: #f1f1f1;
			width: 100px;
			height: 100px;
			font-size: 100px;
			display: block;
			line-height: 77px;
			border: 1px solid #007cba;
			border-radius: 5px;
			color: #007cba;
			cursor: pointer;
		}
		.bfb-content-box{
		background-color: #f1f1f1;
		border-color: #d9f6ff;
		color: #444;
		padding: inherit;
		border-radius: 3px;
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		display: flex;
    	padding: 5px;
		}
		.bfb-content-box > div {
			float: left;
			display: block;
		}
		.bfb-content-box > div:nth-child(1) {
			padding: 10px;
		}
	</style>


	<div class="wrap" style="display: flex;">
		<?php $arr_image = explode(";",$idimg); ?>
		<div class='image-preview-wrapper'>
			<?php
			if ( $idimg ):
				$imageDb = 0;
			foreach ( $arr_image as $k => $v ):
				$imageDb++;
			?>
			<div class="imageFrame">
				<div class="checkImage">
					<input type="checkbox" id='<?php _e($imageDb) ?>' value="<?php _e($v) ?>" onClick="lmEasySlider_checkDelete(<?php _e($imageDb) ?>)">
				</div>
				<div class="immagine">
					<img src='<?php echo wp_get_attachment_url($v); ?>' height='100' id="<?php _e($imageDb) ?>">
				</div>

			</div>

			<?php	endforeach; ?>
			<?php	endif; ?>
			<div class='button-preview-wrapper'>
				<div class="imageFrame">
					<span id="upload_image_button" class="button-plus"/>+</span>
				</div>
			</div>
		</div>
		<input type="hidden" id="result" name="result" value="">
	</div>
	<div class="bfb-content-box">
		<div>
			<span class="dashicons dashicons-warning">
			</span>	
		</div>
		<div>
			<?php _e('To add images use the "+" button and select one or more images', 'lm-easy-slider') ?>
			<br> 
			<?php _e('To remove one or more images, select them individually and update the slider.the "+" button and select one or more images.', 'lm-easy-slider') ?>
		</div>
	</div>



 	<?php
 }
 ?>