<?php

add_action( 'after_setup_theme', 'seed_ahoy', 16 );

function seed_ahoy() {
    // launching operation header cleanup
    add_action( 'init', 'seed_head_cleanup' );
    add_filter( 'the_generator', 'seed_rss_version' );
    add_filter( 'wp_head', 'seed_remove_wp_widget_recent_comments_style', 1 );
    add_action( 'wp_head', 'seed_remove_recent_comments_style', 1 );
    add_filter( 'gallery_style', 'seed_gallery_style' );
    add_filter( 'widget_text', 'do_shortcode');
    seed_theme_support();
    add_filter('body_class', 'theme_body_class');
    add_filter( 'the_content', 'seed_filter_ptags_on_images' );
    add_filter( 'excerpt_more', 'seed_excerpt_more' );
    add_action('admin_menu', 'remove_admin_menus');
}


function seed_head_cleanup() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'style_loader_src', 'seed_remove_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'seed_remove_wp_ver_css_js', 9999 );
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);
	remove_action( 'wp_head', 'feed_links_extra', 3 ); 
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action('wp_head', 'rel_canonical');
	remove_action( 'wp_head', 'wp_shortlink_wp_head');
}

function seed_rss_version() {
	return '';
}

function seed_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

function seed_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

function seed_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

function seed_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}

function seed_theme_support() {

	// wp thumbnails
	add_theme_support( 'post-thumbnails' );

	// Custom thumbnail sizes (add as many as you like) - Or use a plugin. It's easier.

	// add_image_size( 'general-thumb-600', 600, 150, true );
	// add_image_size( 'general-thumb-300', 300, 100, true );

	// wp custom background

	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',  // background image default
	    'default-color' => '', // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus

	
}

//==============================================================================
// ASSORTED RANDOM CLEANUP ITEMS
//==============================================================================

function seed_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link and adds a swanky Bootstrap button and icon

function seed_excerpt_more($more) {
	global $post;
	// edit here if you like
	return;
}
function no_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}
 
add_action( 'pre_ping', 'no_self_ping' );


//==============================================================================
// ADD BODY CLASSES
//==============================================================================

function theme_body_class($classes) {
	global $post;
	if (!$post) return $classes;
	$classes[] = 'page-'.$post->post_name;
	if ($post->post_parent) {
		$ppost = get_post($post->post_parent);
		$classes[] = 'section-'.$ppost->post_name;
	}
	return $classes;
}

//==============================================================================
// REMOVE TOP LEVEL ADMIN PAGES FROM SIDE MENU
//==============================================================================

function remove_admin_menus() {
    // remove_menu_page( 'edit.php' ); // posts
    remove_menu_page( 'edit-comments.php' ); // comments
    // remove_menu_page( 'edit.php?post_type=page' ); // pages
}

//==============================================================================
// REMOVE TOP LEVEL ADMIN PAGES FROM NAV BAR
//==============================================================================

function mytheme_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'mytheme_admin_bar_render');

//==============================================================================
// CUSTOMISE TITLE TAG
//==============================================================================

add_filter( 'wp_title', 'rw_title', 10, 3 );
function rw_title( $title, $sep, $seplocation ) {
    global $page, $paged;

    // Don't affect in feeds.

    if ( is_feed() )
            return $title;

    // Add the blog name

    if ( 'right' == $seplocation )
            $title .= get_bloginfo( 'name' );
    else
            $title = get_bloginfo( 'name' ) . $title;

    // Add the blog description for the home/front page.

    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
            $title .= " {$sep} {$site_description}";

    // Add a page number if necessary:

    if ( $paged >= 2 || $page >= 2 )
            $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
            return $title;
}

//==============================================================================
// DASHBOARD WIDGET OVERRIDES
//==============================================================================

function remove_dashboard_meta() {
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}
add_action( 'admin_init', 'remove_dashboard_meta' );

//==============================================================================
// REMOVE SOME USELESS MENU ITEMS UNDER Appearance
//==============================================================================

function remove_menu_items(){
	global $submenu;
	unset($submenu['themes.php'][6]); // remove customize link
}
add_action( 'admin_menu', 'remove_menu_items');
function remove_background_menu_item() {
	remove_theme_support( 'custom-background' );
}
add_action( 'after_setup_theme','remove_background_menu_item', 100 );

//==============================================================================
// CUSTOM ADMIN LOGIN LOGO
//==============================================================================

function custom_login_logo() {
	echo '<style type="text/css">h1 a { background-image: url('.get_bloginfo('template_directory').'/build/images/custom-login-logo.png) !important; height:82px!important; background-size:164px!important; width:200px!important;}</style>';
}
add_action('login_head', 'custom_login_logo');

add_filter('acf/settings/remove_wp_meta_box', '__return_true');

add_editor_style('editor-style.css');


function override_mce_options($initArray) {
	$opts = '*[*]';
	$initArray['valid_elements'] = $opts;
	$initArray['extended_valid_elements'] = $opts;
	return $initArray;
}
add_filter('tiny_mce_before_init', 'override_mce_options');

// REMOVE EMOJI

/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
   }
   add_action( 'init', 'disable_emojis' );
   
   /**
	* Filter function used to remove the tinymce emoji plugin.
	* 
	* @param array $plugins 
	* @return array Difference betwen the two arrays
	*/
   function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}
   }
   
   /**
	* Remove emoji CDN hostname from DNS prefetching hints.
	*
	* @param array $urls URLs to print for resource hints.
	* @param string $relation_type The relation type the URLs are printed for.
	* @return array Difference betwen the two arrays.
	*/
   function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
	/** This filter is documented in wp-includes/formatting.php */
	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
   
   $urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
   
   return $urls;
   }