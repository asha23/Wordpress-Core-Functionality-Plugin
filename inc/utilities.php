<?php

// Text limits

function getFirstPara($string){
    $string = substr($string,0, strpos($string, "</p>")+4);
    $string = str_replace("<p>", "", str_replace("<p/>", "", $string));
    return $string;
}

function limit_text($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . '...';
	}
	return $text;
}

/* ========================================================================================================================

Wrap Wordpress Video Shortcode in Responsive Wrapper

======================================================================================================================== */

function lookupIDfromURL($image_url) {

	// basic lookup from DB to match media URL with media URL
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 

}

add_filter( 'wp_video_shortcode', function( $output ) {

	// get SRC 
	// this is a bit hacky

	preg_match( '@src="([^"]+)"@' , $output, $match );
	$src = array_pop($match);
	$src = preg_replace('/\?.*/', '', $src);

	// get ID

	$postid = lookupIDfromURL( $src );
	$meta = wp_get_attachment_metadata($postid);

	// let it autoplay 
	// and include playsinline to fix issues on iOS

	$output = str_replace( "<video", "<video muted playsinline ", $output );
	//$output = str_replace( "controls=", "data-controls=", $output );
	
	// wrap it all up

	$str = preg_replace('/\<[\/]{0,1}div[^\>]*\>/i', '', $output);
	$padding = ($meta['height']/$meta['width'])*100;
	$wrap = "<div class='embed-responsive' style='padding-bottom:".$padding."%'>".$str."</div>";
    
	$output = $wrap;
	return $output;

} );