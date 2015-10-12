<?php
/*
Plugin Name: WP Nice Gallery
Version: 1.1
Description: Replaces the default gallery function with a nicer one
Author: Nico Hauser & Michael Ziörjen

*/

add_action('wp_enqueue_scripts', 'wp_nice_gallery_css');

remove_shortcode('gallery');
add_shortcode('gallery', 'wp_nice_gallery_func');

add_action('wp_footer', 'wp_nice_gallery_footer_scripts');

function wp_nice_gallery_func($atts){
	
	global $wp_nice_gallery_counter, $wp_nice_galleries;
	
	if(!isset($wp_nice_gallery_counter)){
		$wp_nice_gallery_counter='a';
	}
	if(!isset($wp_nice_galleries) || !is_array($wp_nice_galleries)){
		$wp_nice_galleries=array();
	}
	
	extract( shortcode_atts( array(
		/*'link' => '',*/
		'ids' => '',
		/*'orderby' => '',*/
	), $atts ) );

	if(!empty($ids)){
		$ids_ = explode(",", $ids);

		$code="<div class='row'>";
		$first = true;
		foreach ($ids_ as $id) {
			$post = get_post($id);
			
			$code.='<div class="col-xs-6 col-md-3">
			<a href="'.wp_get_attachment_url($id).'" class="thumbnail" data-imagelightbox="'.$wp_nice_gallery_counter.'"><img src="'.wp_get_attachment_thumb_url($id).'" alt="'.$post->post_excerpt.'"/></a>
			</div>';
	    }
		$code.= "</div>";
		
		$wp_nice_galleries[]=$wp_nice_gallery_counter;
		
		//add to $wp_nice_gallery_counter
		$wp_nice_gallery_counter = ++$wp_nice_gallery_counter;
		
		

		wp_reset_query();

		return $code;
	}

}

function wp_nice_gallery_css() {
	wp_enqueue_script('lightbox-script', plugins_url('js/imagelightbox.min.js', __FILE__), array('jquery'), false, true);
	wp_enqueue_style('lightbox-css', plugins_url('css/lightbox.css', __FILE__));
}

function wp_nice_gallery_footer_scripts(){
	global $wp_nice_galleries;
	
	
	echo "<script>jQuery(document).ready(function() {".
		"var galleries=".json_encode($wp_nice_galleries).";".
		"if(galleries!= null) {".
			"for(var i=0;i<galleries.length;i++){".
				"niceGallery(galleries[i]);".
			"}".
		"}".
	"});".
	"</script>";
}

?>