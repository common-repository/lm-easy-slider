<?php
/**
 * Registers LM Easy Slider post type.
 */

function LMEasySlider_menu() {

	$labels = array(
		'name'               => __( 'LM Easy Slider', 'lm-easy-slider' ),
		'singular_name'      => __( 'LM Easy Slider', 'lm-easy-slider' ),
		'add_new'            => __( 'Add new slider', 'lm-easy-slider' ),
		'add_new_item'       => __( 'Add new slider', 'lm-easy-slider' ),
		'edit_item'          => __( 'Edit slider', 'lm-easy-slider' ),
		'new_item'           => __( 'Add new slider', 'lm-easy-slider' ),
		'view_item'          => __( 'View slider', 'lm-easy-slider' ),
		'search_items'       => __( 'Search slider', 'lm-easy-slider' ),
		'not_found'          => __( 'Slider not found', 'lm-easy-slider' ),
		'not_found_in_trash' => __( 'Slider not found in the recycle bin', 'lm-easy-slider' )
	);

	$supports = array(
		'title',
	);
	

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'lm-easy-slider' ),
		'has_archive'          => true,
		'menu_position'        => 30,
		'menu_icon'            => 'dashicons-images-alt',
		'register_meta_box_cb' => 'add_lmeslider_metaboxes',
	);

register_post_type( 'lm-easy-slider', $args );

}
add_action( 'init', 'LMEasySlider_menu' );


function add_lmeslider_metaboxes() {
	global $post;
	$lmEasySliderId = 'lm-easy-slider-'.$post->ID;
	add_meta_box(
		'lm_easy_slider',
		$lmEasySliderId,
		'lm_easy_slider',
		'lm-easy-slider',
		'normal',
		'high'
	);
	
	add_meta_box(
		'lm_easy_slider_options',
		//'Opzioni slider',
		__( 'Slider options', 'lm-easy-slider' ),
		'lm_easy_slider_options',
		'lm-easy-slider',
		'normal',
		'high'
	);

}
// Questo è utile per aggiungere colonne in un post_type
// manage_***_posts_columns (al posto degli *** mettere lo slug )
add_filter( 'manage_lm-easy-slider_posts_columns', 'set_custom_edit_lmeslider_columns' );


function set_custom_edit_lmeslider_columns( $columns ) {
 $date = $colunns['date'];
  unset( $columns['date'] ); 
  $columns['shortcode'] = __( 'ShortCode', 'lm-easy-slider' );
  $columns['numImg'] =  '<span class="dashicons dashicons-images-alt"></span>';
  $columns['date'] = __( 'Date', 'lm-easy-slider' );
  return $columns;
}


add_action( 'manage_lm-easy-slider_posts_custom_column' , 'custom_lmeslider_column', 10, 2 );

function custom_lmeslider_column( $column, $post_id ) {
	
	switch ( $column ) {
		case 'shortcode' :
			$short = '[LMEasySlider slider_id="lm-easy-slider-'.$post_id.'"]';
			echo $short;
			break;
		case 'numImg' :
			$numImg = lm_easy_slider_countImg($post_id );
			echo $numImg;
			break;
	}
}

function lm_easy_slider_countImg($post_id ) {
	global $wpdb;
	$lmEasySliderId = 'lm-easy-slider-'.$post_id;
	$res_select = $wpdb->get_row("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '$lmEasySliderId'");
	$numImages = 0;
	if ($res_select != '' ) {		
		$slider_value = $res_select ->meta_value;
		$slider = json_decode( $slider_value );
 		$idimg = lmeasy_control_media_esistenti($slider->idimg, $post_id );
		if($idimg != ''){
			$slider_value = explode (";",$idimg);
			$numImages = count($slider_value);	
		}
	} 
	return $numImages;
	
}

function lm_easy_slider() {
	global $post;
	global $lmes_plugin;
	$lmes_plugin='lm-easy-slider';
	// Nonce field to validate form request came from current site
	wp_nonce_field( LMESLIDER_BASENAME_FILE, 'lmeslider_fields' );
	LMEasySlider_modifica_slider();
	}
function lm_easy_slider_options() {
	global $post;
	global $lmes_plugin;
	$lmes_plugin='lm-easy-slider';
	// Nonce field to validate form request came from current site
	wp_nonce_field( LMESLIDER_BASENAME_FILE, 'lmeslider_fields' );
	LMEasySlider_opzioni_slider();
}



