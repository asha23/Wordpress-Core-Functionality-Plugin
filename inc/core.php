<?php

namespace arlo;

final class core
{
	public function __construct()
	{

	}

	public function listen()
	{
		add_action( 'after_setup_theme', [$this, 'seed_ahoy'], 16 );
		add_action( 'admin_init', [$this, 'seed_remove_dashboard_meta'] );
		add_action( 'admin_menu', [$this, 'seed_remove_menu_items']);
		add_action( 'after_setup_theme', [$this, 'seed_remove_background_menu_item'], 100 );
		add_action('init', [$this, 'seed_rss_version']);
		add_filter( 'the_generator', [$this, 'seed_rss_version'] );
		add_filter( 'wp_head', [$this, 'seed_remove_wp_widget_recent_comments_style'], 1 );
		add_action( 'wp_head', [$this, 'seed_remove_recent_comments_style'], 1 );
		add_filter( 'gallery_style', [$this, 'seed_gallery_style'] );
		add_filter( 'widget_text', [$this, 'seed_do_shortcode']);
		add_filter('body_class', [$this, 'seed_theme_body_class']);
		add_filter( 'the_content', [$this, 'seed_filter_ptags_on_images'] );
		add_filter( 'excerpt_more', [$this, 'seed_excerpt_more'] );
		add_action('admin_menu', [$this, 'seed_remove_admin_menus']);

		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'style_loader_src', [$this, 'seed_remove_wp_ver_css_js'], 9999 );
		add_filter( 'script_loader_src', [$this, 'seed_remove_wp_ver_css_js'], 9999 );

		add_action('login_head', [$this, 'custom_login_logo']);


		add_filter('acf/settings/remove_wp_meta_box', '__return_true');

		$this->seed_theme_support();
	}

	public function seed_rss_version() {
		return '';
	}

	public function seed_remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}

	public function seed_remove_wp_widget_recent_comments_style() {
		if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
			remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
		}
	}
	
	public function seed_remove_recent_comments_style() {
		global $wp_widget_factory;

		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
		}
	}
	
	public function seed_gallery_style($css) {
		return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
	}

	public function seed_theme_support() {

		add_theme_support( 'post-thumbnails' );
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
	
		register_nav_menus(
			array(
				'main-nav' => __( 'The Main Menu', 'SEEDtheme' ),   // main nav in header
				'footer-links' => __( 'Footer Links', 'SEEDtheme' ) // secondary nav in footer
			)
		);
	}

	public function seed_filter_ptags_on_images($content){
		return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}

	public function seed_excerpt_more($more) {
		global $post;
		// edit here if you like
		return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __( 'Read', 'SEEDtheme' ) . get_the_title($post->ID).'">'. __( '<p>&nbsp;</p><button class="btn btn-info">Read more <i class="fa fa-angle-double-right"></i></button>', 'SEEDtheme' ) .'</a>';
	}

	public function seed_theme_body_class($classes) {
		global $post;
		if (!$post) return $classes;
		$classes[] = 'page-'.$post->post_name;
		if ($post->post_parent) {
			$ppost = get_post($post->post_parent);
			$classes[] = 'section-'.$ppost->post_name;
		}
		return $classes;
	}

	public function seed_remove_admin_menus() {
		remove_menu_page( 'edit-comments.php' ); // comments
	}

	public function seed_remove_dashboard_meta() {
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

	public function seed_remove_menu_items(){
		global $submenu;
		unset($submenu['themes.php'][6]); // remove customize link
	}

	
	function seed_remove_background_menu_item() {
		remove_theme_support( 'custom-background' );
	}

	public function seed_custom_login_logo() {
		echo '<style type="text/css">h1 a { background-image: url('.get_bloginfo('template_directory').'/build/images/custom-login-logo.png) !important; height:82px!important; background-size:164px!important; width:200px!important;}</style>';
	}

}