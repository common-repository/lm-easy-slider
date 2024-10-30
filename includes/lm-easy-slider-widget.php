<?php
/**
 * LMEasySlider selector widget
 *
 * Crea un widget in cui è possibile inserire un titolo e uno slider
 * creati con il plugin "LMEasySlider" e un pulsante per un link
 *
 * @author leonardoboss, marsy79
 * @version 1.0
 * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
 * @copyright Copyright (c) 2020, leonardoboss, marsy79
 */


class LMEasySlider extends WP_Widget {


	public
	function __construct() {
		parent::__construct(
			'LMEasySlider', // ID del widget
			__( 'LMEasySlider', 'lm-easy-slider' ), //Nome del widget che appare nell'interfaccia
			array( 'description' => __( 'Insert a slider!', 'lm-easy-slider' ),
				'customize_selective_refresh' => true,
			) //Descrizione del Widget
		);
	}
	

	function widget( $args, $instance ) {
		// PART 1: Extracting the arguments + getting the values
		extract( $args, EXTR_SKIP );
		$title = empty( $instance[ 'title' ] ) ? ' ' : apply_filters( 'widget_title', $instance[ 'title' ] );
		$subtitle = empty( $instance[ 'subtitle' ] ) ? ' ' : apply_filters( 'subheading', $instance[ 'subtitle' ] );
		$slider = empty( $instance[ 'slider' ] ) ? '' : $instance[ 'slider' ];
		
		// Before widget code, if any
		echo( isset( $before_widget ) ? $before_widget : '' );

		// PART 2: The title and the text output
		if ( !empty( $title ) )echo $before_title . $title . $after_title;
		if ( !empty( $subtitle ) )echo '<p align="center">' . $subtitle . '</p>';
?>
		<div style="height: 60px"></div>
<?php
		if ( !empty( $slider ) )echo do_shortcode( '[LMEasySlider slider_id="' . $slider . '"]' );
		// After widget code, if any  
		echo( isset( $after_widget ) ? $after_widget : '' );
	}

	public
	function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) )$title = $instance[ 'title' ];
		else $title = __( 'New title', 'lm-easy-slider' );

		if ( isset( $instance[ 'subtitle' ] ) )$subtitle = $instance[ 'subtitle' ];
		else $subtitle = __( 'New subtitle', 'lm-easy-slider' );

		if ( isset( $instance[ 'slider' ] ) )$slider = $instance[ 'slider' ];
		else $slider = __( 'Select a slider', 'lm-easy-slider' );

		// PART 2-3: Display the fields		
?>
		<p>
			<!-- PART 2: Widget Title field START -->
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'lm-easy-slider')?>: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />			
		</p> <!-- Widget Title field END -->
		<p>
			<!-- PART 2: Widget Sottotitolo field START -->
			<label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle', 'lm-easy-slider')?>: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>" />
		</p> <!-- Widget Sottotitolo field END -->
		<!-- PART 3: Widget Slider field START -->
		<p>
<?php
			global $wpdb;
			$rows = $wpdb->get_results( "SELECT $wpdb->posts.post_title, $wpdb->postmeta.meta_key from $wpdb->postmeta, $wpdb->posts WHERE $wpdb->postmeta.meta_key LIKE 'lm-easy-slider%'and $wpdb->postmeta.post_id = $wpdb->posts.ID and $wpdb->posts.post_status = 'publish'" );
			$rowcount = $wpdb->num_rows;?>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Slider', 'lm-easy-slider')?>: </label>
			<select class='widefat' id="<?php echo $this->get_field_id('slider'); ?>" name="<?php echo $this->get_field_name('slider'); ?>" type="text">
<?php 
				foreach ($rows as &$arr_result):		
					foreach ($arr_result as $k => $v):
						if ($k == 'post_title')	$post_title = trim($v);
						if ($k == 'meta_key') $valore = trim($v); 
						if ($post_title !='' && $valore !='') :?>
							  <option value='<?php echo ($valore) ?>'<?php echo($slider==$valore)?'selected':''; ?>>
								<?php echo ($post_title); ?>
							  </option>
							  <?php unset($post_tite, $valore);
						endif;
					endforeach;
				endforeach; ?>
			</select>   
		</p>
		<!-- Widget Slider field END -->
<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		$instance[ 'subtitle' ] = $new_instance[ 'subtitle' ];
		$instance[ 'slider' ] = $new_instance[ 'slider' ];
		
		return $instance;
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("LMEasySlider");' ) );
?>