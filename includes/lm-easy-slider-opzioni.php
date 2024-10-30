 
<?php
function LMEasySlider_opzioni_slider() {
    global $wpdb;
	global $post;
	$lmEasySliderId = 'lm-easy-slider-'.$post->ID;
	
	$res_select = $wpdb->get_row("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '$lmEasySliderId'");

	if ($res_select != '' ) {
		$slider_value = $res_select ->meta_value;
		$slider = json_decode($slider_value);
		$frecce = $slider->frecce;
		$indicatori = $slider->indicatori;
		$descrizione = $slider->descrizione;
		$titolo = $slider->titolo;
		$radioChecked = $slider->angoli;
		$speed = $slider->speed;
		$pausa = $slider->pausa;
		$blocco = $slider->blocco;
	} else {
		$frecce = 'no';
		$indicatori = 'no';
		$descrizione ='no';
		$titolo = 'no';
		$radioChecked ='';
		$speed = '3000';
		$pausa = 'false';
		$blocco = 'false';
	}
?>
	<div class="checkFrecce">
		<input type="checkbox" name="frecce" id="frecce" value="<?php echo $frecce?>" onClick="lmEasySlider_setCheckFrecce();" <?php if($frecce=='si') echo 'checked' ?>> <b><?php _e('Displays scroll arrows' ,'lm-easy-slider')?><span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, it enables the use of arrows allowing the scrolling of images in the desired direction" ,'lm-easy-slider')?>"></span></b>
	</div>
	<div class="checkIndicatori">
		<input type="checkbox" name="indicatori" id="indicatori" value="<?php echo $indicatori?>" onClick="lmEasySlider_setCheckIndicatori();" <?php if($indicatori=='si') echo 'checked' ?>> <b><?php _e('Displays the indicators below the slider' ,'lm-easy-slider')?> <span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, it enables the position indicators of the slider, allowing direct viewing of the image while scrolling" ,'lm-easy-slider')?>"></span></b>
	</div>
	<div class="checkDescrione">
		<input type="checkbox" name="descrizione" id="descrizione" value="<?php echo $descrizione?>" onClick="lmEasySlider_setCheckDescr();"  <?php if($descrizione=='si') echo 'checked' ?>> <b><?php _e('View the description of the images' ,'lm-easy-slider')?> <span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, it enables the display of the description in the image" ,'lm-easy-slider')?>"></span></b>
	</div>
	<div class="checkTitolo">
		<input type="checkbox" name="titolo" id="titolo" value="<?php echo $titolo?>" onClick="lmEasySlider_setCheckTitolo();"  <?php if($titolo=='si') echo 'checked' ?>> <b><?php _e('Displays the title of the images' ,'lm-easy-slider')?> <span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, it enables the display of the title in the image" ,'lm-easy-slider')?>"></span></b>
	</div>
	<div class="">
		<input type="radio" name="angoli" id="angoli_tondi" value="angoli_tondi" <?php if ($radioChecked == 'angoli_tondi' || $radioChecked == '') echo 'checked' ?>> <b><?php _e('Rounded corners' ,'lm-easy-slider')?></b>
		<input type="radio" name="angoli" id="angoli_quadri" value="angoli_quadri" <?php if ($radioChecked == 'angoli_quadri') echo 'checked' ?>> <b><?php _e('Square corners' ,'lm-easy-slider')?></b>
	</div>
	<div class="">
		<b><?php _e('Insert the scroll speed of the slider' ,'lm-easy-slider')?></b>
		<input type="text" name="speed" id="speed" value="<?php echo $speed?>" > 
	</div>
	<div class="">
		<input type="checkbox" name="pausa" id="pausa" value="<?php echo $pausa?>" onClick="lmEasySlider_setCheckPausa();"  <?php if($pausa=='hover') echo 'checked' ?>> <b><?php _e('Lock the slider on hover' ,'lm-easy-slider')?> <span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, it enables image freezing until the mouse exits the slider" ,'lm-easy-slider')?>"></span></b>
	</div>
	<div class="">
		<input type="checkbox" name="blocco" id="blocco" value="<?php echo $blocco?>" onClick="lmEasySlider_setCheckBlocco();"  <?php if($blocco=='true') echo 'checked' ?>> <b><?php _e('The slider always cycles' ,'lm-easy-slider')?> <span class="dashicons dashicons-info" data-toggle="tooltip" data-placement="bottom" title="<?php _e("If selected, the slider will lock on the last image" ,'lm-easy-slider')?>"></span></b>
	</div>
<script>
	

function lmEasySlider_setCheckFrecce () {
	if (jQuery('#frecce').val() == 'no') {
		jQuery('#frecce').attr('checked',true);
		jQuery('#frecce').val('si');
		return;
	}
	else if (jQuery('#frecce').val() == 'si') {
		jQuery('#frecce').attr('checked',false);
		jQuery('#frecce').val('no');
		return;
	}
}
function lmEasySlider_setCheckIndicatori () {
	if (jQuery('#indicatori').val() == 'no') {
		jQuery('#indicatori').attr('checked',true);
		jQuery('#indicatori').val('si');
		return;
	}
	else if (jQuery('#indicatori').val() == 'si') {
		jQuery('#indicatori').attr('checked',false);
		jQuery('#indicatori').val('no');
		return;
	}
}	
	
function lmEasySlider_setCheckDescr () {
	if (jQuery('#descrizione').val() == 'no') {
		jQuery('#descrizione').attr('checked',true);
		jQuery('#descrizione').val('si');
		return;
	}
	else if (jQuery('#descrizione').val() == 'si') {
		jQuery('#descrizione').attr('checked',false);
		jQuery('#descrizione').val('no');
		return;
	}
}

function lmEasySlider_setCheckTitolo () {
	if (jQuery('#titolo').val() == 'no') {
		jQuery('#titolo').attr('checked',true);
		jQuery('#titolo').val('si');
		return;
	}
	else if (jQuery('#titolo').val() == 'si') {
		jQuery('#titolo').attr('checked',false);
		jQuery('#titolo').val('no');
		return;
	}
}
function lmEasySlider_setCheckPausa () {
	if (jQuery('#pausa').val() == 'false') {
		jQuery('#pausa').attr('checked',true);
		jQuery('#pausa').val('hover');
		return;
	}
	else if (jQuery('#pausa').val() == 'hover') {
		jQuery('#pausa').attr('checked',false);
		jQuery('#pausa').val('false');
		return;
	}
}
function lmEasySlider_setCheckBlocco () {
	if (jQuery('#blocco').val() == 'false') {
		jQuery('#blocco').attr('checked',true);
		jQuery('#blocco').val('true');
		return;
	}
	else if (jQuery('#blocco').val() == 'true') {
		jQuery('#blocco').attr('checked',false);
		jQuery('#blocco').val('false');
		return;
	}
}
	jQuery(document).ready(function(){		
		var nImages = jQuery(".image-preview-wrapper > .imageFrame").length;
		
		if (nImages < 2){
			jQuery('#frecce').prop('disabled',true);
			jQuery('#indicatori').prop('disabled',true);
			jQuery('#pausa').prop('disabled',true);
			jQuery('#blocco').prop('disabled',true);
		} else{
			jQuery('#frecce').prop('disabled',false);
			jQuery('#indicatori').prop('disabled',false);
			jQuery('#pausa').prop('disabled',false);
			jQuery('#blocco').prop('disabled',false);
		} 
	});
</script>

<?php
}        
?>
 






		
