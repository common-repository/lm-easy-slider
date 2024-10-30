<?php

/**
 * Save the metabox data
 */
function lmEasy_save_slider_meta( $post_id, $post ) {
	global $post;
	global $wpdb;


	// Return if the user doesn't have edit permissions.
	if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;


	if ( !isset( $_POST[ 'submit_image_selector' ] ) && ( !isset( $_POST[ 'myplugin_attachment_id_array' ] ) &&
			( !isset( $_POST[ 'result' ] ) ) &&
			( !isset( $_POST[ 'descrizione' ] ) ) &&
			( !isset( $_POST[ 'frecce' ] ) ) &&
			( !isset( $_POST[ 'indicatori' ] ) ) &&
			( !isset( $_POST[ 'titolo' ] ) ) &&
			( !isset( $_POST[ 'angoli' ] ) ) &&
			( !isset( $_POST[ 'speed' ] ) ) &&
			( !isset( $_POST[ 'pausa' ] ) ) &&
			( !isset( $_POST[ 'blocco' ] ) )
		) ||
		!wp_verify_nonce( $_POST[ 'lmeslider_fields' ], LMESLIDER_BASENAME_FILE )

	) return $post_id;



	/*Elementi aggiunti*/
	//print_r($_POST[ 'myplugin_attachment_id_array' ] );
	//exit(0);
	$valueImgAdd = '';
	if ( isset( $_POST[ 'myplugin_attachment_id_array' ] ) ) {
		//$arr_id_image = $_POST['myplugin_attachment_id_array'];	
		$arr_id_image = array_map( 'sanitize_text_field', wp_unslash( $_POST[ 'myplugin_attachment_id_array' ] ) );	
		

		foreach ( $arr_id_image as $k => $v ) {
			if ( $k == 0 )$valueImgAdd = sanitize_text_field( $v );
			else $valueImgAdd = $valueImgAdd . ";" . sanitize_text_field( $v );
		}
	}

	$key = 'lm-easy-slider-' . $post_id;

	/*Elementi presenti nel db*/
	$idimgDb = '';
	$idimgCaric = '';
	$res_select = $wpdb->get_row( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '$key'" );
	if ( $res_select != '' ) {
		$slider_valueDb = $res_select->meta_value;
		$slider = json_decode( $slider_valueDb );
		$idimgDb = $slider->idimg;
	}

	if ( $valueImgAdd != '' ) {
		if ( $idimgDb != '' ) {
			$idimgCaric = $idimgDb . ";" . $valueImgAdd;
		} else {
			$idimgCaric = $valueImgAdd;
		}
	} else if ( $idimgDb != '' ) {
		$idimgCaric = $idimgDb;
	}


	/*Elementi check da cancellare*/
	if ( isset( $_POST[ 'result' ] ) ) {

		if ( substr( $_POST[ 'result' ], -1, 1 ) == ';' ) {
			$arr_id_imageCanc = substr( sanitize_text_field( $_POST[ 'result' ] ), 0, -1 );
		} else {
			$arr_id_imageCanc = sanitize_text_field( $_POST[ 'result' ] );
		}
		$arr_imageCanc = explode( ";", $arr_id_imageCanc );
		foreach ( $arr_imageCanc as $k => $v ) {
			$idimgCaric = str_replace_first( $v, '', $idimgCaric );
			$idimgCaric = str_replace( ';;', ';', $idimgCaric );

		}
	}
	if ( substr( $idimgCaric, 0, 1 ) == ';' ) {
		$slider_value = substr( $idimgCaric, 1 );
	}
	if ( substr( $idimgCaric, -1, 1 ) == ';' ) {
		$slider_value = substr( $idimgCaric, 0, -1 );
	}

	$value = $idimgCaric;

	if ( substr( $value, 0, 1 ) == ';' ) {
		$value = substr( $value, 1 );
	}
	if ( substr( $value, -1, 1 ) == ';' ) {
		$value = substr( $value, 0, -1 );
	}
	$value = str_replace( ';;', ';', $value );

	$numImages = 0;

	if ( $value != '' ) {
		$numImages = count( explode( ";", $value ) );
	}

	if ( $numImages < 2 ) {
		$frecce = 'no';
		$indicatori = 'no';
		$pausa = 'false';
		$blocco = 'false';
	} else {
		if ( $_POST[ 'frecce' ] == 'si' )$frecce = 'si';
		else $frecce = 'no';
		if ( $_POST[ 'indicatori' ] == 'si' )$indicatori = 'si';
		else $indicatori = 'no';
		if ( $_POST[ 'pausa' ] == 'hover' )$pausa = 'hover';
		else $pausa = 'false';
		if ( $_POST[ 'blocco' ] == 'true' )$blocco = 'true';
		else $blocco = 'false';
	}


	if ( $_POST[ 'descrizione' ] == 'si' )$descrizione = 'si';
	else $descrizione = 'no';
	if ( $_POST[ 'titolo' ] == 'si' )$titolo = 'si';
	else $titolo = 'no';
	if ( $_POST[ 'speed' ] != '' )$speed = sanitize_text_field( $_POST[ 'speed' ] );
	else $blocco = '3000';
	if ( isset( $_POST[ 'angoli' ] ) )$angoli = sanitize_text_field( $_POST[ 'angoli' ] );

	$punti_meta[ 'idimg' ] = sanitize_meta( 'idimg', $value, 'post' );
	$punti_meta[ 'descrizione' ] = sanitize_meta( 'descrizione', $descrizione, 'post' );
	$punti_meta[ 'frecce' ] = sanitize_meta( 'frecce', $frecce, 'post' );
	$punti_meta[ 'indicatori' ] = sanitize_meta( 'indicatori', $indicatori, 'post' );
	$punti_meta[ 'titolo' ] = sanitize_meta( 'titolo', $titolo, 'post' );
	$punti_meta[ 'angoli' ] = sanitize_meta( 'angoli', $angoli, 'post' );
	$punti_meta[ 'speed' ] = sanitize_meta( 'speed', $speed, 'post' );
	$punti_meta[ 'pausa' ] = sanitize_meta( 'pausa', $pausa, 'post' );
	$punti_meta[ 'blocco' ] = sanitize_meta( 'blocco', $blocco, 'post' );
	$lmEasyJson = json_encode( $punti_meta );

	if ( 'revision' === $post->post_type ) return;

	// If the custom field already has a value, update it.
	if ( get_post_meta( $post_id, $key, false ) )update_post_meta( $post_id, $key, $lmEasyJson );
	// If the custom field doesn't have a value, add it.
	else add_post_meta( $post_id, $key, $lmEasyJson );

	// Delete the meta key if there's no value
	if ( !$lmEasyJson )delete_post_meta( $post_id, $key );
}
add_action( 'save_post', 'lmEasy_save_slider_meta', 1, 2 );