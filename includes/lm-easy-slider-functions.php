<?php

require_once (LMESLIDER_INCLUDE_PATH . 'lm-easy-slider-menu.php');
require_once (LMESLIDER_INCLUDE_PATH . 'lm-easy-slider-modifica.php');
require_once (LMESLIDER_INCLUDE_PATH . 'lm-easy-slider-widget.php');
require_once (LMESLIDER_INCLUDE_PATH . 'lm-easy-slider-save.php');
require_once (LMESLIDER_INCLUDE_PATH . 'lm-easy-slider-opzioni.php');

function LMESLIDER_load_textdomain() {
	load_plugin_textdomain( 'lm-easy-slider', false, LMESLIDER_BASENAME_DIR.'/languages/' ); 
}
add_action('plugins_loaded', 'LMESLIDER_load_textdomain');

function LMEasySlider_shortcut( $atts ) {
	$a = shortcode_atts( array(
		'slider_id' => ''
	), $atts );
	$sliderString ='';
	include LMESLIDER_FRONT_END_PATH . 'slider.php'; 
	return $sliderString;
}
add_shortcode( 'LMEasySlider', 'LMEasySlider_shortcut' );

add_filter( 'widget_text', 'do_shortcode' );



if (!function_exists('wp_get_attachment')) {
    function wp_get_attachment( $attachment_id ) {
        $attachment = get_post( $attachment_id );
        return array (
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink( $attachment->ID ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title,
			'link' => get_post_meta( $attachment->ID, 'custom_media_style', true ),
        );
    }
}

function str_replace_first($from, $to, $content) {
    $from = '/'.preg_quote($from, '/').'/';
    return preg_replace($from, $to, $content, 1);
}

add_action( 'admin_footer', 'lmeasySlider_media_selector_print_scripts' );


function lmeasySlider_media_selector_print_scripts() {
	
?>
	 <script type='text/javascript'> 
		 
		jQuery(document).ready( function( $ ) {
			var myplugin_media_upload;
			
			jQuery('#upload_image_button').click(function(e) {
			e.preventDefault();

			// Se l'ogetto uploader è stato già creato, riapro il dialog box
			if (myplugin_media_upload) {
				myplugin_media_upload.open();			
				return;
			}

			// Estendo l'ogetto wp.media
				
			myplugin_media_upload = wp.media.frames.file_frame = wp.media ({
				title: "<?php _e( 'Add', 'lm-easy-slider' )?>" , //_e( 'Aggiungi', 'lm-easy-slider' )
				button: { text: "<?php _e( 'Use these images', 'lm-easy-slider' )?>" },
				multiple: true //permette la selezione multipla delle immagine
			});
				
			/**
			 *THE KEY BUSINESS
			 *Quando sono selezionate immagini multiple, prendo gli ogetti allegati
			 *e li converto in un array di allegati usabili
			 */

			myplugin_media_upload.on( 'select', function(){
				var attachments = myplugin_media_upload.state().get('selection').map( 
					function( attachment ) {
						attachment.toJSON();
					  return attachment;
					});
				//loop through the array and do things with each attachment
				var i;
				for (i = 0; i < attachments.length; ++i) { 
					//Aggiungo la preview dell'immagine
					$('<div class="imageFrame"><div class="checkImage"><input type="checkbox" id="new'+attachments[i].id+'" value="'+attachments[i].id+'" onClick="lmEasySlider_checkDelete(\'new'+attachments[i].id+'\')"></div><div class="immagine"><img src="'+attachments[i].attributes.url+'" height="100" id="img-'+attachments[i].id+'"></div></div>').insertBefore('.button-preview-wrapper');
					//Aggiungo un campo input per ogni immagine
					$('<input id="myplugin-image-inputnew'+attachments[i].id+'" type="hidden" name="myplugin_attachment_id_array[]" value="'+attachments[i].id+'">').insertBefore('.button-preview-wrapper');
					nImages = jQuery(".image-preview-wrapper > .imageFrame").length;
				}
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
			myplugin_media_upload.open();
			
			});
			

			/*Per disabilitare il submit quando il titolo è vuoto */
			<?php global $lmes_plugin;
			if ($lmes_plugin == 'lm-easy-slider') { ?>
			
				jQuery('.button').click(function(){

					//imposto una variabile e ci associo l'attributo id del trigger
				   //che ho cliccato (in questo caso .button)
					var recupero_id = jQuery(this).attr("id");	

					//da qui in poi potete usare l'id recuperato per fare qualcosa
					//in questo caso disabilito il submit quando il titolo è vuoto per l'id recuperato.
					if(jQuery(this).attr("id") === 'publish'){
						jQuery( "form" ).submit(function( event ) {
							if ( jQuery('#title').val() === "" ){
								event.preventDefault();
								alert ("<?php _e( 'Insert a title', 'lm-easy-slider' )?>");
							} 
							else return;
						});
						jQuery('#title').on('keyup keypress change click', function(e) {
							if(jQuery('#title').val() == '') jQuery('#publish').prop('disabled',true);
							else jQuery('#publish').prop('disabled',false); 
						});
					}

				}); //fine click function
			<?php } ?>
			
			
		});

		var stringaIdCheck = '';
		function lmEasySlider_checkDelete (idImg) {	
			var stringaIdCheck = '';
			jQuery('.checkImage > input[type=checkbox]:checked').each(function(index){
				stringaIdCheck += jQuery(this).val() + ';'
			});	
			
			jQuery("#result").val(stringaIdCheck);
		}


	</script>
<?php
}

/*** Funzione per aggiungere un custom field nella libreria dei media ***/
		
function lmeasySlider_custom_media_add_media_custom_field( $form_fields, $post ) {
    $field_value = get_post_meta( $post->ID, 'custom_media_style', true );
    $form_fields['custom_media_style'] = array(
        'value' => $field_value ? $field_value : '',
        'label' => __( 'Link', 'lm-easy-slider' ),
        'helps' => __( "Insert a link to go when clicking on the image", 'lm-easy-slider'),
        'input'  => 'text'
    );
    return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'lmeasySlider_custom_media_add_media_custom_field', null, 2 ); 

function lmeasy_control_media_esistenti($stingaIdImages, $post_id ) {
	global $wpdb;
	$lmEasySliderId = 'lm-easy-slider-'.$post_id;
	$newString = str_replace(';',',',$stingaIdImages);
	if($newString !=''){
		$res_select = $wpdb->get_results( "SELECT id FROM $wpdb->posts WHERE id IN($newString)" );
		$idimgPresDB="";
		foreach ($res_select as &$arr_result) {
			foreach ($arr_result as $k => $v) { 
				if ($k == 'id') {					
					if ($idimgPresDB == '') $idimgPresDB =$v;
					else $idimgPresDB =$idimgPresDB.";".$v;					
				}
			}
		}
		$arry_imgCaricare='';
		$array_imgPassate = explode(";", $stingaIdImages);
		$array_imgSuDB = explode(";", $idimgPresDB);
		foreach ($array_imgPassate as $imgPassate) {
			foreach ($array_imgSuDB as $imgSuDB) {
				if(strpos($imgPassate,$imgSuDB) !== false ){
					if ($arry_imgCaricare == '') $arry_imgCaricare =$imgPassate;
						else $arry_imgCaricare =$arry_imgCaricare.";".$imgPassate;
				}
			}
		}
	
		if ($arry_imgCaricare != '')$stingaIdImages = $arry_imgCaricare;
		
		$idimgDbMeta='';
		$res_selectMeta = $wpdb->get_row("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '$lmEasySliderId'");
		if ($res_selectMeta != '' ) {
			$slider_valueDbMeta = $res_selectMeta ->meta_value;
			$sliderMeta = json_decode($slider_valueDbMeta);

			$sliderMeta->idimg = $arry_imgCaricare;
			$json_objectLm = json_encode($sliderMeta);
			update_post_meta($post_id, $lmEasySliderId, $json_objectLm);
		} 

	}
			
	
	return $stingaIdImages;
}

//save your custom media field
function lmeasySlider_custom_media_save_attachment( $attachment_id ) {
    if ( isset( $_REQUEST['attachments'][ $attachment_id ]['custom_media_style'] ) ) {
        $custom_media_style = sanitize_text_field($_REQUEST['attachments'][ $attachment_id ]['custom_media_style']);
        update_post_meta( $attachment_id, 'custom_media_style', $custom_media_style );

    }
}
add_action( 'edit_attachment', 'lmeasySlider_custom_media_save_attachment' );
